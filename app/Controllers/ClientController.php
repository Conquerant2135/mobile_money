<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\OperationModel;
use App\Models\TransactionModel;
use App\Models\UserModel;
use CodeIgniter\HTTP\ResponseInterface;

class ClientController extends BaseController
{
    protected $transactionModel;
    protected $operationModel;
    protected $userModel;

    public function __construct()
    {
        $this->transactionModel = new TransactionModel();
        $this->operationModel = new OperationModel();
        $this->userModel = new UserModel();
    }
    public function index()
    {

        $filters = [
            'operation_id' => $this->request->getGet('operation_id'),
            'date_debut'   => $this->request->getGet('date_debut'),
            'date_fin'     => $this->request->getGet('date_fin'),
        ];

        $perPageOptions = [5, 10, 25, 50];
        $perPage = (int) $this->request->getGet('per_page');
        if (!in_array($perPage, $perPageOptions, true)) {
            $perPage = 5;
        }

        $historiques = $this->transactionModel->historiqueTransaction(
            session()->get("user_id"),
            $filters,
            $perPage
        );

        return view("client/accueil", [
            'historiques'    => $historiques,
            'pager'          => $this->transactionModel->pager,
            'operations'     => $this->operationModel->findAll(),
            'filters'        => $filters,
            'perPage'        => $perPage,
            'perPageOptions' => $perPageOptions,
        ]);
    }

    public function showSituationCompte()
    {

        return view("operateur/situation_compte_client", ['clientSoldes' => $this->userModel->getClientsWithSolde()]);
    }

    public function operationPage()
    {
        return view("client/operation", ['operations' => $this->operationModel->findAll()]);
    }

    public function operation()
    {
        $data = $this->request->getPost();

        try {
            $this->transactionModel->makeTransaction($data, session()->get("user_id"));
            return redirect()->to("/client")
                ->with('success', 'Opération effectuée avec succès');
        } catch (\RuntimeException $e) {
            return redirect()->to('/client/operation')
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }
}
