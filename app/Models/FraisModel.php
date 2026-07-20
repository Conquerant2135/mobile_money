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

    public function findFraisValueForMontant($montant, $operationId)
    {
        $result = $this
            ->where('min <=', $montant)
            ->where('max >', $montant)
            ->where('operation_id', $operationId)
            ->first();

        if ($result === null) {
            throw new \RuntimeException("Aucun bareme de frais trouvé pour operation_id={$operationId}, montant={$montant}");
        }

        return $result['frais_val'];
    }
}
