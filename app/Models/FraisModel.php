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

    /**
     * Récupère la liste de tous les barèmes de frais avec le nom et code de l'opération
     */
    public function getFraisWithOperation(): array
    {
        return $this->select('frais.*, operation.nom AS operation_nom, operation.code AS operation_code')
                    ->join('operation', 'operation.id = frais.operation_id')
                    ->orderBy('operation.id', 'ASC')
                    ->orderBy('frais.min', 'ASC')
                    ->findAll();
    }

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

    /**
     * Gain total généré par les Transferts (TRA) via la vue
     */
    public function getGainTransfert(): float
    {
        $builder = $this->db->table('v_transaction_and_type_operation');
        $result  = $builder->selectSum('frais_montant', 'total_gain')
                           ->where('code_operation', 'TRA')
                           ->get()
                           ->getRowArray();

        return $result['total_gain'] ? (float) $result['total_gain'] : 0.0;
    }

    /**
     * Gain total généré par les Retraits (RET) via la vue
     */
    public function getGainRetrait(): float
    {
        $builder = $this->db->table('v_transaction_and_type_operation');
        $result  = $builder->selectSum('frais_montant', 'total_gain')
                           ->where('code_operation', 'RET')
                           ->get()
                           ->getRowArray();

        return $result['total_gain'] ? (float) $result['total_gain'] : 0.0;
    }
}