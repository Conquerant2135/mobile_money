<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\NumPrefixeValableModel;
use App\Models\UserModel;
use CodeIgniter\HTTP\ResponseInterface;

class AuthController extends BaseController
{
    public function loginPage()
    {
        return view("auth/login");
    }

    public function login()
    {
        $data = $this->request->getPost();
        $phone = $data["phone"];
        $userModel = new UserModel();
        $numValidator = new NumPrefixeValableModel();

        try {
            if (!$numValidator->isNumValid($phone)) {
                throw new \RuntimeException("Le numéro inscrit est invalide");
            }

            $user = $userModel->findByNumero($phone);
            if (!$user) {
                $userModel->save(['numero' => $phone, 'role' => 'client']);
                $user = $userModel->findByNumero($phone);
            }

            session()->set([
                'user_id' => $user['id'],
                'role' => $user['role'],
                'logged_in' => true
            ]);

            return redirect()->to("/client");
        } catch (\RuntimeException $e) {
            return redirect()->to('/login')
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to("/login");
    }
}
