<?php

namespace App\Models;

use CodeIgniter\Model;

class FraisModel extends Model
{
    protected $table            = 'frais';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';

    protected $allowedFields    = [
        'operation_id',
        'min',
        'max',
        'frais_val'
    ];

    protected $useTimestamps   = false;

    public function findFraisValueForMontant($montant){
        return $this->where( 'min >=' , $montant)->where('max <' , $montant)->first()["frais_val"];
    }
}