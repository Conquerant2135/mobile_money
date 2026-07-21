<?php

namespace App\Models;

use CodeIgniter\Model;

class MvtEpargeModel extends Model
{
    protected $table            = 'mvt_eparge';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';

    protected $allowedFields    = [
      'id_client',  
      'montant',  
    ];

    protected $useTimestamps   = true;
    protected $dateFormat      = 'datetime';
    protected $updatedField    = '';

    public function getValeurEparge($clientId) {
        $this->selectSum("montant")->where('id_client',$clientId)->groupBy('id_client');
    
        return 5000;
    }

}