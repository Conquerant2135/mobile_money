<?php

namespace App\Models;

use CodeIgniter\Model;

class CommissionAutreModel extends Model
{
    protected $table            = 'commission_autres';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['id', 'operateur_id', 'date_debut', 'date_fin', 'pourcentage'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;
    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    public function findOperateurCommission($operateurId)
    {
        return $this
                ->where('operateur_id' , $operateurId)
                ->where('date_fin' , null)
                ->first();
    }
}
