<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\OperationModel;

class OperationController extends BaseController
{
    protected $operationModel;

    public function __construct()
    {
        $this->operationModel = new OperationModel();
    }

    // GET : Liste + formulaire d'ajout
    public function list()
    {
        $data['operations'] = $this->operationModel->findAll();
        return view('operateur/operation/index', $data);
    }

    // POST : Création d'une opération
    public function create()
    {
        $rules = [
            'nom'  => 'required|min_length[3]|max_length[50]',
            'code' => 'required|is_unique[operation.code]|min_length[2]|max_length[10]|alpha_numeric'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->operationModel->insert([
            'nom'  => $this->request->getPost('nom'),
            'code' => strtoupper($this->request->getPost('code'))
        ]);

        return redirect()->to('/operateur/operations')->with('success', 'Type d\'opération ajouté avec succès.');
    }

    // GET : Formulaire de modification
    public function modification($id)
    {
        $operation = $this->operationModel->find($id);

        if (!$operation) {
            return redirect()->to('/operateur/operations')->with('error', 'Opération introuvable.');
        }

        $data['operation'] = $operation;
        return view('operateur/operation/edit', $data);
    }

    // POST : Traitement de la modification
    public function update($id)
    {
        $rules = [
            'nom'  => 'required|min_length[3]|max_length[50]',
            'code' => "required|min_length[2]|max_length[10]|alpha_numeric|is_unique[operation.code,id,{$id}]"
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->operationModel->update($id, [
            'nom'  => $this->request->getPost('nom'),
            'code' => strtoupper($this->request->getPost('code'))
        ]);

        return redirect()->to('/operateur/operations')->with('success', 'Type d\'opération mis à jour.');
    }

    // GET : Suppression
    public function delete($id)
    {
        if ($this->operationModel->find($id)) {
            $this->operationModel->delete($id);
            return redirect()->to('/operateur/operations')->with('success', 'Opération supprimée.');
        }

        return redirect()->to('/operateur/operations')->with('error', 'Opération introuvable.');
    }
}