<?php

namespace App\Models;

use CodeIgniter\Model;

class SoldeCompteClientModel extends Model
{
    protected $table            = 'v_solde_compte_client';
    protected $primaryKey       = 'user_id';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';

    // Lecture seule (pas d'insert / update / delete sur une vue)
    protected $allowedFields    = [];


    // Récupère le solde d'un client spécifique par son ID
    public function getSoldeByUserId(int $userId): float
    {
        $result = $this->where('user_id', $userId)->first();
        
        return $result ? (float) $result['solde'] : 0.0;
    }


    // Vérifie si un client a un solde suffisant pour effectuer un retrait ou un transfert
    public function hasSoldeSuffisant(int $userId, float $montantTotal): bool
    {
        $soldeActuel = $this->getSoldeByUserId($userId);

        return $soldeActuel >= $montantTotal;
    }
}