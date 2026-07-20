<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\NumPrefixeValableModel;

class NumPrefixeValableController extends BaseController
{
    protected $prefixModel;

    public function __construct()
    {
        $this->prefixModel = new NumPrefixeValableModel();
    }

    // GET : Liste + formulaire d'ajout
    public function list()
    {
        $data['prefixes'] = $this->prefixModel->findAll();
        return view('operateur/prefix/index', $data);
    }

    // POST : Création d'un préfixe
    public function create()
    {
        $rules = [
            'prefix' => 'required|is_unique[num_prefixe_valable.prefix]|min_length[3]|max_length[5]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->prefixModel->insert([
            'prefix' => $this->request->getPost('prefix'),
            'actif'  => $this->request->getPost('actif') ? 1 : 0
        ]);

        return redirect()->to('/operateur/prefixes')->with('success', 'Préfixe ajouté avec succès.');
    }

    // GET : Formulaire de modification
    public function modification($id)
    {
        $prefix = $this->prefixModel->find($id);

        if (!$prefix) {
            return redirect()->to('/operateur/prefixes')->with('error', 'Préfixe introuvable.');
        }

        $data['prefix'] = $prefix;
        return view('operateur/prefix/edit', $data);
    }

    // POST : Traitement de la modification
    public function update($id)
    {
        $rules = [
            'prefix' => "required|min_length[3]|max_length[5]|is_unique[num_prefixe_valable.prefix,id,{$id}]"
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->prefixModel->update($id, [
            'prefix' => $this->request->getPost('prefix'),
            'actif'  => $this->request->getPost('actif') ? 1 : 0
        ]);

        return redirect()->to('/operateur/prefixes')->with('success', 'Préfixe mis à jour avec succès.');
    }

    // GET : Suppression d'un préfixe
    public function delete($id)
    {
        if ($this->prefixModel->find($id)) {
            $this->prefixModel->delete($id);
            return redirect()->to('/operateur/prefixes')->with('success', 'Préfixe supprimé.');
        }

        return redirect()->to('/operateur/prefixes')->with('error', 'Préfixe introuvable.');
    }
}