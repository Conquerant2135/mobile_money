<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\FraisModel;
use App\Models\OperationModel;
use App\Models\TransactionModel;
use App\Models\UserModel;
use CodeIgniter\HTTP\ResponseInterface;

class FraisController extends BaseController
{

    public function showGainPerOperation()
    {
        $fraisModel = new FraisModel();

        return view("operateur/gain_par_frais_operation", ['gainRetait' => $fraisModel->getGainRetrait(),'gainTransfert' => $fraisModel->getGainTransfert()]);
    }
}
