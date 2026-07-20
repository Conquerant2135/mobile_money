<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\OperationModel;
use App\Models\TransactionModel;
use CodeIgniter\HTTP\ResponseInterface;

class ClientController extends BaseController
{
    public function index()
    {
        $transactionModel = new TransactionModel();
        $operationModel   = new OperationModel();

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

        $historiques = $transactionModel->historiqueTransaction(
            session()->get("user_id"),
            $filters,
            $perPage
        );

        return view("client/accueil", [
            'historiques'    => $historiques,
            'pager'          => $transactionModel->pager,
            'operations'     => $operationModel->findAll(),
            'filters'        => $filters,
            'perPage'        => $perPage,
            'perPageOptions' => $perPageOptions,
        ]);
    }

    public function operationPage()
    {
        $operationModel = new OperationModel();
        return view("client/operation", ['operations' => $operationModel->findAll()]);
    }

    public function operation()
    {
        $data = $this->request->getPost();
        $transctionModel = new TransactionModel();
        $transctionModel->makeTransaction($data, session()->get("user_id"));
        return redirect()->to("/client");
    }
}
