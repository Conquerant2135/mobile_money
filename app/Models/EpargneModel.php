<?php

namespace App\Models;

use CodeIgniter\Model;

class EpargneModel extends Model
{
    protected $table            = 'epargne';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';

    protected $allowedFields    = [
        'id',
        'id_client',
        'prct',
    ];
    protected $dateFormat      = 'datetime';
    protected $updatedField    = '';

    public function getPourcentage($idClient)
    {
        return $this->where('id_client', $idClient)->first()['prct'];
    }

    public function  effectuerEpargne($numClient, $montant) {
        $mvtEpargeModel = new MvtEpargeModel();
        $userModel = new UserModel();

        //recuecher le cleitn
        $clientId =  $userModel->findByNumero($numClient)['id'];

        $pourcentageEparge = $this->getPourcentage($clientId);

        $montantAEpargner = $montant * $pourcentageEparge/100;

        $mvtEpargeModel->save([
            'id_client' => $clientId,
            'montant' => $montantAEpargner
        ]);
    }

    /**
     * Récupère tous les clients avec leur solde actuel
     * 
     * @return array
     */
    public function getClientsWithSolde(): array
    {
        return $this->select('users.*, COALESCE(v_solde_compte_client.solde, 0) AS solde')
                    ->join('v_solde_compte_client', 'v_solde_compte_client.user_id = users.id', 'left')
                    ->where('users.role', 'client')
                    ->findAll();
    }

    
    //Récupère un client spécifique avec son solde par son ID
    public function getClientWithSoldeById(int $userId): ?array
    {
        return $this->select('users.*, COALESCE(v_solde_compte_client.solde, 0) AS solde')
                    ->join('v_solde_compte_client', 'v_solde_compte_client.user_id = users.id', 'left')
                    ->where('users.role', 'client')
                    ->where('users.id', $userId)
                    ->first();
    }
}