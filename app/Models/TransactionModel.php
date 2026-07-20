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
            $this->makeDepotRetrait($data, $userId, false);
        } else {
            $this->makeTransfert($data, $userId);
        }
    }

    public function makeDepotRetrait($data, $userId, $isDepot)
    {
        $fraisModel  = new FraisModel();
        $userModel = new UserModel();
        $montant = $isDepot ? $data['montant'] : -1 * $data['montant'];
        $frais = $isDepot ? 0 : $fraisModel->findFraisValueForMontant($data['montant'], $data['operation']);
        if ($data['montant'] + $frais < $userModel->getClientWithSoldeById($userId)) {
            throw new RuntimeException("Le solde est insuffisant pour cette action");
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
        $fraisModel = new FraisModel();
        $userModel = new UserModel();
        $numValidator = new NumPrefixeValableModel();
        $frais = $fraisModel->findFraisValueForMontant($data['montant'], $data['operation']);
        if (!$numValidator->isNumValid($data['phone'])) {
            throw new \RuntimeException(" Le numero inscrit est invalide ");
        }
        if ($data['montant'] + $frais < $userModel->getClientWithSoldeById($userId)) {
            throw new RuntimeException("Le solde est insuffisant pour cette action");
        }
        $dest = $userModel->findByNumero($data['phone']);
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
