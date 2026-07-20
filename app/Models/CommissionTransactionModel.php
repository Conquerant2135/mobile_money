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
    
    // N'inclut pas 'id' dans allowedFields (géré automatiquement par AUTOINCREMENT)
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
}