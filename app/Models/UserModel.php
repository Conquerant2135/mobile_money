<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';

    protected $allowedFields    = [
        'id',
        'nom',
        'prenom',
        'role',
        'numero',
        'date_naissance',
        'mot_de_passe',
        'created_at'
    ];

    protected $useTimestamps   = true;
    protected $dateFormat      = 'datetime';
    protected $createdField    = 'created_at';
    protected $updatedField    = '';

    public function findByNumero($phone)
    {
        return $this->where('numero', $phone)->first();
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