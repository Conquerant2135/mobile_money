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
        'actif',
        'operateur_id'
    ];

    protected $useTimestamps   = false;

    public function isNumValid($num)
    {
        return $this->checkAgainstPrefixes($num, true);
    }

    public function isKnownNum($num)
    {
        return $this->checkAgainstPrefixes($num, false);
    }

    private function checkAgainstPrefixes($num, $onlyOurs)
    {
        $num = preg_replace('/\s+/', '', $num);
        if (!preg_match('/^\d{10}$/', $num)) {
            return false;
        }

        $builder = $this
            ->select('num_prefixe_valable.prefix')
            ->join('operateur', 'operateur.id = num_prefixe_valable.operateur_id')
            ->where('num_prefixe_valable.actif', 1);

        if ($onlyOurs) {
            $builder->where('operateur.a_nous', 1);
        }

        $prefixesActifs = $builder->findColumn('prefix') ?? [];

        foreach ($prefixesActifs as $prefix) {
            $longueurReste = 10 - strlen($prefix);
            $pattern = '/^' . preg_quote($prefix, '/') . '\d{' . $longueurReste . '}$/';
            if (preg_match($pattern, $num)) {
                return true;
            }
        }

        return false;
    }

    public function getOperateur(string $phone): ?array
    {
        $phone = preg_replace('/\s+/', '', $phone);

        if (!preg_match('/^\d{10}$/', $phone)) {
            return null;
        }

        $prefixes = $this->select('num_prefixe_valable.prefix, operateur.id, operateur.nom, operateur.a_nous')
                         ->join('operateur', 'operateur.id = num_prefixe_valable.operateur_id')
                         ->where('num_prefixe_valable.actif', 1)
                         ->findAll();

        foreach ($prefixes as $item) {
            $prefix = $item['prefix'];
            $longueurReste = 10 - strlen($prefix);
            $pattern = '/^' . preg_quote($prefix, '/') . '\d{' . $longueurReste . '}$/';

            if (preg_match($pattern, $phone)) {
                return [
                    'id'     => $item['id'],
                    'nom'    => $item['nom'],
                    'a_nous' => $item['a_nous']
                ];
            }
        }

        return null;
    }
}