<?php

namespace App\Models;

use CodeIgniter\Model;
use RuntimeException;
use App\Models\NumPrefixeValableModel;
use App\Models\FraisModel;
use App\Models\UserModel;


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
        'num_dest',
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
            $this->makeTransfert($data, $userId);
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

    public function makeTransfert($data, $userId)
    {
        $fraisModel        = new FraisModel();
        $userModel         = new UserModel();
        $numValidator      = new NumPrefixeValableModel();
        $commissionManager = new CommissionTransactionModel();

        $frais = $fraisModel->findFraisValueForMontant($data['montant'], $data['operation']);

        $solde = $userModel->getClientWithSoldeById($userId);
        $totalADebiter = $data['montant'] + $frais;

        if ($totalADebiter > $solde["solde"]) {
            throw new \RuntimeException(
                "Le solde est insuffisant pour cette action. Votre solde : " . $solde["solde"] .
                    ", montant total requis (avec frais) : " . $totalADebiter
            );
        }

        $dest = $userModel->findByNumero($data['phone']);

        if (!$dest && !$numValidator->isKnownNum($data['phone'])) {
            throw new \RuntimeException("Le numero destinataire : " . $data['phone'] . " n'est pas valide ou reconnu.");
        }

        $transactionData = [
            'user_id'         => $userId,
            'operation_id'    => $data['operation'],
            'destinataire_id' => $dest['id'] ?? null,
            'num_dest'        => $data['phone'],
            'montant'         => $data['montant'],
            'frais_montant'   => $frais,
            'description'     => $data['desc'] ?? null
        ];

        $insertedId = $this->insert($transactionData);

        if (!$insertedId) {
            throw new \RuntimeException("Erreur lors de l'enregistrement de la transaction.");
        }

        $commissionManager->appliquerCommission($insertedId, $data['phone'], $data['montant']);

        return $insertedId;
    }

    public function historiqueTransaction($userId, $filters = [], $perPage = 5)
    {
        $builder = $this
            ->select('
            transactions.id as ref,
            operation.nom as operation,
            COALESCE(destinataire.numero, transactions.num_dest) as destinataire_numero,
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