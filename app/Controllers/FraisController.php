<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\FraisModel;
use App\Models\OperationModel;
use App\Models\CommissionTransactionModel;

class FraisController extends BaseController
{
    protected $fraisModel;
    protected $operationModel;
    protected $commissionModel;

    public function __construct()
    {
        $this->fraisModel     = new FraisModel();
        $this->operationModel = new OperationModel();
        $this->commissionModel = new CommissionTransactionModel();
    }

    // Affichage des gains par opération
    public function showGainPerOperation()
    {

        return view("operateur/gain_par_frais_operation", [
            'gainRetait'    => $this->fraisModel->getGainRetrait(),
            'gainTransfert' => $this->fraisModel->getGainTransfert(),
            'totaux' => $this->commissionModel->getMontantsARendreParOperateur()
        ]);
    }

    // GET : Liste des frais + formulaire d'ajout
    public function list()
    {
        $data['frais_list'] = $this->fraisModel->getFraisWithOperation();
        $data['operations'] = $this->operationModel->findAll();

        return view('operateur/frais/index', $data);
    }

    // POST : Création d'une plage de frais
    public function create()
    {
        $this->fraisModel->insert([
            'operation_id' => $this->request->getPost('operation_id'),
            'min'          => $this->request->getPost('min'),
            'max'          => $this->request->getPost('max'),
            'frais_val'    => $this->request->getPost('frais_val')
        ]);

        return redirect()->to('/operateur/frais')->with('success', 'Nouveau barème de frais ajouté avec succès.');
    }

    // GET : Formulaire de modification
    public function modification($id)
    {
        $frais = $this->fraisModel->find($id);

        if (!$frais) {
            return redirect()->to('/operateur/frais')->with('error', 'Barème de frais introuvable.');
        }

        $data['frais']      = $frais;
        $data['operations'] = $this->operationModel->findAll();

        return view('operateur/frais/edit', $data);
    }

    // POST : Traitement de la modification
    public function update($id)
    {
        $this->fraisModel->update($id, [
            'operation_id' => $this->request->getPost('operation_id'),
            'min'          => $this->request->getPost('min'),
            'max'          => $this->request->getPost('max'),
            'frais_val'    => $this->request->getPost('frais_val')
        ]);

        return redirect()->to('/operateur/frais')->with('success', 'Barème de frais mis à jour.');
    }

    // GET : Suppression
    public function delete($id)
    {
        if ($this->fraisModel->find($id)) {
            $this->fraisModel->delete($id);
            return redirect()->to('/operateur/frais')->with('success', 'Barème supprimé.');
        }

        return redirect()->to('/operateur/frais')->with('error', 'Barème introuvable.');
    }
}