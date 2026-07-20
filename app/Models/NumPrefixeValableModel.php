<?php

namespace App\Models;

use CodeIgniter\Model;

class NumPrefixeValableModel extends Model
{
    protected $table            = 'num_prefixe_valable';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';

    protected $allowedFields    = [
        'prefix',
        'actif'
    ];

    protected $useTimestamps   = false;
}