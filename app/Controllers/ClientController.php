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
        return view("client/accueil");
    }

    public function operationPage(){
        $operationModel = new OperationModel();
        return view("client/operation" , ['operations' => $operationModel->findAll()]);
    }

    public function operation(){
        $data = $this->request->getPost();
        $transctionModel = new TransactionModel();
        $transctionModel->makeTransaction($data,session()->get("user_id"));
    }


}
