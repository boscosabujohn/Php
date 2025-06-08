<?php
namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\UserModel;

class UserController extends ResourceController
{
    public function login()
    {
        $input = $this->request->getJSON(true) ?? $this->request->getPost();
        $email = $input['email'] ?? '';
        $password = $input['password'] ?? '';
        $userModel = new UserModel();
        $user = $userModel->login($email, $password);
        if ($user) {
            return $this->respond(['status' => 'success', 'user' => $user]);
        }
        return $this->failUnauthorized('Invalid credentials');
    }
}
