<?php
namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\FmsOtherModel;

class FmsOtherController extends ResourceController
{
    public function call()
    {
        $input = $this->request->getJSON(true) ?? $this->request->getPost();
        $procedure = $input['procedure'] ?? '';
        $params = $input['params'] ?? [];
        $model = new FmsOtherModel();
        $result = $model->callProcedure($procedure, $params);
        return $this->respond($result);
    }

    public function getUserByKey()
    {
        $input = $this->request->getJSON(true) ?? $this->request->getPost() ?? $this->request->getGet();
        $key = $input['key'] ?? null;
        if (!$key) {
            return $this->respond(['status' => 'error', 'message' => 'Key is required'], 400);
        }
        $model = new \App\Models\FmsOtherModel();
        $result = $model->getUserByKey($key);
        return $this->respond(['status' => 'ok', 'data' => $result]);
    }

    public function updateUserPasswordByKey()
    {
        $input = $this->request->getJSON(true) ?? $this->request->getPost();
        $key = $input['key'] ?? null;
        $password = $input['password'] ?? null;
        if (!$key || !$password) {
            return $this->respond(['status' => 'error', 'message' => 'Key and password are required'], 400);
        }
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $model = new \App\Models\FmsOtherModel();
        $affected = $model->updateUserPasswordByKey($key, $hashedPassword);
        if ($affected > 0) {
            return $this->respond(['status' => 'ok', 'message' => 'Password updated successfully']);
        } else {
            return $this->respond(['status' => 'error', 'message' => 'No user updated'], 404);
        }
    }
}
