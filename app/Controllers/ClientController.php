<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\OperationModel;
use App\Models\TransactionModel;
use App\Models\UserModel;
use CodeIgniter\HTTP\ResponseInterface;

class ClientController extends BaseController
{
    public function index()
    {
        return view("client/accueil");
    }

    public function showSituationCompte() {
        $userModel = new UserModel();

        return view("operateur/situation_compte_client" , ['clientSoldes' => $userModel->getClientsWithSolde()]);
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
