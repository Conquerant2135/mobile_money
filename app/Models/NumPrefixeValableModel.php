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

    public function isNumValid($num)
    {
        $num = preg_replace('/\s+/', '', $num);

        if (!preg_match('/^\d{10}$/', $num)) {
            return false;
        }

        $prefixesActifs = $this->where('actif', 1)->findColumn('prefix');

        if (empty($prefixesActifs)) {
            return false;
        }

        foreach ($prefixesActifs as $prefix) {
            $longueurReste = 10 - strlen($prefix);
            $pattern = '/^' . preg_quote($prefix, '/') . '\d{' . $longueurReste . '}$/';

            if (preg_match($pattern, $num)) {
                return true;
            }
        }

        return false;
    }
}
