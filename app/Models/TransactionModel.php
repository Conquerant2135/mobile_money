<?php

namespace App\Models;

use CodeIgniter\Model;
use RuntimeException;

class TransactionModel extends Model
{
    protected $table            = 'transactions';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';

    protected $allowedFields    = [
        'user_id',
        'operation_id',
        'destinataire_id',
        'montant',
        'frais_montant',
        'description',
        'date_op'
    ];

    protected $useTimestamps   = true;
    protected $dateFormat      = 'datetime';
    protected $createdField    = 'date_op';
    protected $updatedField    = '';

    public function makeTransaction($data, $userId)
    {
        if ($data['operation'] == 1) {
            $this->makeDepotRetrait($data, $userId, true);
        } elseif ($data['operation'] == 2) {
            $frais_inclus = isset($data['frais_inclus']);
            if ($frais_inclus) {
                $this->makeRetraitWithFrais($data, $userId);
            } else {
                $this->makeDepotRetrait($data, $userId, false);
            }
        } else {
            //$this->makeTransfert($data, $userId);
            $this->makeTransferMultiNumero($data, $userId);
        }
    }

    public function makeRetraitWithFrais($data, $userId)
    {
        //car c est un retrait
        $fraisModel  = new FraisModel();
        $userModel = new UserModel();
        $frais = $fraisModel->findFraisValueForMontant($data['montant'], $data['operation']);
        $solde = $userModel->getClientWithSoldeById($userId);
        if ($data['montant'] - $frais > $solde["solde"]) {
            throw new RuntimeException("Le solde est insuffisant pour cette action votre solde : " . $solde["solde"] . " La transaction " . ($data['montant'] - $frais));
        }
        $this->save([
            'montant' => $data['montant']  - $frais,
            'operation_id' => $data['operation'],
            'frais_montant' => 0,
            'user_id' => $userId
        ]);
    }

    public function makeDepotRetrait($data, $userId, $isDepot)
    {
        $fraisModel  = new FraisModel();
        $userModel = new UserModel();
        $montant = $data['montant']; // Toujours positif
        $frais = $isDepot ? 0 : $fraisModel->findFraisValueForMontant($data['montant'], $data['operation']);
        $solde = $userModel->getClientWithSoldeById($userId);
        if (!$isDepot && $data['montant'] + $frais > $solde["solde"]) {
            throw new RuntimeException("Le solde est insuffisant pour cette action votre solde : " . $solde["solde"] . " La transaction " . ($data['montant'] + $frais));
        }
        $this->save([
            'montant' => $montant,
            'operation_id' => $data['operation'],
            'frais_montant' => $frais,
            'user_id' => $userId
        ]);
    }

   public function makeTransferMultiNumero($data, $userId)
    {
        $fraisModel = new FraisModel();
        $userModel  = new UserModel();

        // 1. Nettoyage de la liste des numéros (suppression des champs vides)
        $phones = array_filter((array)($data['phone'] ?? []));

        if (empty($phones)) {
            throw new RuntimeException("Veuillez renseigner au moins un numéro de téléphone.");
        }

        $nbPhones = count($phones);
        $montantTotal = $data['montant'];

        // 2. Calcul du montant divisé par destinataire et des frais associés
        $montantAVerser = $montantTotal / $nbPhones;
        $fraisPourUnNum = $fraisModel->findFraisValueForMontant($montantAVerser, $data['operation']);

        $fraisTotal = $fraisPourUnNum * $nbPhones;
        $solde = $userModel->getClientWithSoldeById($userId);

        // 3. Vérification du solde de l'expéditeur
        if ($montantTotal + $fraisTotal > $solde["solde"]) {
            throw new RuntimeException("Le solde est insuffisant pour cette action. Votre solde : " . $solde["solde"] . " Ar | Montant nécessaire : " . ($montantTotal + $fraisTotal) . " Ar");
        }

        // 4. Exécution des transferts vers chaque destinataire
        foreach ($phones as $numDest) {
            $this->transfererMonantVersNum(
                $data['operation'],
                $montantAVerser,
                $fraisPourUnNum,
                $userId,
                $numDest,
                $data['desc'] ?? null
            );
        }
    }
   public function transfererMonantVersNum($operationId, $montant, $frais, $idUser, $numDest, $desc)
    {
        $numValidator = new NumPrefixeValableModel();
        if (!$numValidator->isNumValid($numDest)) {
            throw new \RuntimeException("Le numéro " . $numDest . " inscrit est invalide");
        }

        $userModel = new UserModel();
        $dest = $userModel->findByNumero($numDest);

        if (!$dest) {
            throw new RuntimeException("Le client ayant le numéro : " . $numDest . " n'existe pas");
        }

        $this->save([
            'user_id'         => $idUser,
            'operation_id'    => $operationId,
            'destinataire_id' => $dest['id'],
            'montant'         => $montant,
            'frais_montant'   => $frais,
            'description'     => $desc
        ]);
    }


    public function makeTransfert($data, $userId)
    {
        $fraisModel = new FraisModel();
        $userModel = new UserModel();
        $numValidator = new NumPrefixeValableModel();
        $frais = $fraisModel->findFraisValueForMontant($data['montant'], $data['operation']);
        if (!$numValidator->isNumValid($data['phone'])) {
            throw new \RuntimeException(" Le numero inscrit est invalide ");
        }
        $solde = $userModel->getClientWithSoldeById($userId);
        if ($data['montant'] + $frais > $solde["solde"]) {
            throw new RuntimeException("Le solde est insuffisant pour cette action votre solde : " . $solde["solde"] . " La transaction " . ($data['montant'] + $frais));
        }
        $dest = $userModel->findByNumero($data['phone']);
        if (!$dest) {
            throw new RuntimeException("Le client ayant le numero  : " . $data['phone'] . " n'existe pas");
        }
        $this->save([
            'user_id' => $userId,
            'operation_id' => $data['operation'],
            'destinataire_id' => $dest['id'],
            'montant' => $data['montant'],
            'frais_montant' => $frais,
            'description' => $data['desc']
        ]);
    }

    public function historiqueTransaction($userId, $filters = [], $perPage = 5)
    {
        $builder = $this
            ->select('
            transactions.id as ref,
            operation.nom as operation,
            destinataire.numero as destinataire_numero,
            transactions.montant,
            transactions.frais_montant as frais,
            transactions.description,
            transactions.date_op
        ')
            ->join('operation', 'operation.id = transactions.operation_id')
            ->join('users as destinataire', 'destinataire.id = transactions.destinataire_id', 'left')
            ->where('transactions.user_id', $userId);

        if (!empty($filters['operation_id'])) {
            $builder->where('transactions.operation_id', $filters['operation_id']);
        }

        if (!empty($filters['date_debut'])) {
            $builder->where('transactions.date_op >=', $filters['date_debut'] . ' 00:00:00');
        }

        if (!empty($filters['date_fin'])) {
            $builder->where('transactions.date_op <=', $filters['date_fin'] . ' 23:59:59');
        }

        return $builder->orderBy('transactions.date_op', 'DESC')->paginate($perPage);
    }
}
