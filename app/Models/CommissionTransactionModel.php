<?php

namespace App\Models;

use CodeIgniter\Model;

class CommissionTransactionModel extends Model
{
    protected $table            = 'commission_transaction';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    protected $allowedFields    = ['transaction_id', 'operateur_id', 'montant_comm'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    // Mettre à false car la table SQL ne contient pas created_at/updated_at
    protected $useTimestamps = false;

    /**
     * Calcule et enregistre la commission pour un opérateur tiers
     */
    public function appliquerCommission($transId, $phone, $montant)
    {
        $numValidator = new NumPrefixeValableModel();
        $operateur = $numValidator->getOperateur($phone);

        if (!$operateur) {
            return false;
        }

        if (!empty($operateur['a_nous']) && $operateur['a_nous'] == 1) {
            return false;
        }

        $operateurId = $operateur['id'];

        $otherModel = new CommissionAutreModel();
        $commissionData = $otherModel->findOperateurCommission($operateurId);

        if (!$commissionData || !isset($commissionData['pourcentage'])) {
            return false;
        }

        $pct = (float) $commissionData['pourcentage'];

        $montantComm = $montant * ($pct / 100);

        // 5. Sauvegarde
        return $this->insert([
            'transaction_id' => $transId,
            'operateur_id'   => $operateurId,
            'montant_comm'   => $montantComm
        ]);
    }

    public function getMontantsARendreParOperateur()
    {
        return $this->db->table('solde_par_operateur')
            ->get()
            ->getResultArray();
    }

    public function getDetailMontantsARendre($filters = [])
    {
        $builder = $this->db->table('commission_transaction ct')
            ->select('
                o.id as operateur_id,
                o.nom as operateur_nom,
                SUM(ct.montant_comm) as total_commission,
                COUNT(ct.id) as nombre_transactions
            ')
            ->join('operateur o', 'o.id = ct.operateur_id')
            ->join('transactions t', 't.id = ct.transaction_id')
            ->where('o.a_nous', 0);

        if (!empty($filters['date_debut'])) {
            $builder->where('t.date_op >=', $filters['date_debut'] . ' 00:00:00');
        }

        if (!empty($filters['date_fin'])) {
            $builder->where('t.date_op <=', $filters['date_fin'] . ' 23:59:59');
        }

        if (!empty($filters['operateur_id'])) {
            $builder->where('o.id', $filters['operateur_id']);
        }

        return $builder->groupBy('o.id, o.nom')
            ->get()
            ->getResultArray();
    }
}
