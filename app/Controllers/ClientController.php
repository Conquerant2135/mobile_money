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

        $historiques = $transactionModel->historiqueTransaction(session()->get("user_id"));

        return view("client/accueil", [
            'historiques' => $historiques,
            'pager'       => $transactionModel->pager,
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
