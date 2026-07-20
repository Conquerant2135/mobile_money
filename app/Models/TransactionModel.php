<?php

namespace App\Models;

use CodeIgniter\Model;

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

    public function makeTransaction($data , $userId){
        if($data['operation'] == 1){
            $this->makeDepotTransfert($data,$userId,true);
        } elseif ($data['operation'] == 2) {
            $this->makeDepotTransfert($data,$userId,false);
        } else  {
            $this->makeTransfert($data , $userId);
        }
    }

    public function makeDepotTransfert($data , $userId , $isDepot){
        $fraisModel  = new FraisModel();
        $montant = $isDepot ? $data['montant'] : -1 * $data['montant'];
        $frais = $isDepot ? 0 : $fraisModel->findFraisValueForMontant($data['montant']);
        $this->save([
                'montant' => $montant,
                'operation_id' => $data['operation'],
                'frais_montant' => $frais,
                'user_id' => $userId
                ]);
    }

    public function makeTransfert($data , $userId){
        $fraisModel = new FraisModel();
        $userModel = new UserModel();
        $frais = $fraisModel->findFraisValueForMontant($data['montant']);
        $dest = $userModel->findByNumero($data['phone']);
        $this->save([
                'user_id' => $userId,
                'operation_id' => $data['operation'],
                'destination_id' => $dest['id'],
                'montant' => $data['montant'],
                'frais_montant' => $frais,
                'description' => $data['desc']
                ]);
    }
}