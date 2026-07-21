<?php

namespace App\Models;

use CodeIgniter\Model;

class ReductionModel extends Model
{
    protected $table            = 'reduction';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = false;
    
    public function getReduction(){
        return $this->first()['reduct'];
    }
}
