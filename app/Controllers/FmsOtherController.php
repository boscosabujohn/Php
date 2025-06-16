<?php
namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\FmsOtherModel;

class FmsOtherController extends ResourceController
{
    protected $model;

    public function __construct()
    {
        $this->model = new FmsOtherModel();
    }

    public function call()
    {
        $input = $this->request->getJSON(true) ?? $this->request->getPost();
        $procedure = $input['procedure'] ?? '';
        $params = $input['params'] ?? [];
        $result = $this->model->callProcedure($procedure, $params);
        return $this->respond($result);
    }

    public function getUserByKey()
    {
        $input = $this->request->getJSON(true) ?? $this->request->getPost() ?? $this->request->getGet();
        $key = $input['key'] ?? null;
        if (!$key) {
            return $this->respond(['status' => 'error', 'message' => 'Key is required'], 400);
        }
        $result = $this->model->getUserByKey($key);
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
        $affected = $this->model->updateUserPasswordByKey($key, $hashedPassword);
        if ($affected > 0) {
            return $this->respond(['status' => 'ok', 'message' => 'Password updated successfully']);
        } else {
            return $this->respond(['status' => 'error', 'message' => 'No user updated'], 404);
        }
    }

    public function listMaintenanceRequests()
    {
        $data['requests'] = $this->model->fetchRequestsWithJoins();
        return view('maintenance_requests/list', $data);
    }

    public function viewMaintenanceRequest($id)
    {
        $data['request'] = $this->model->getRequestDetails($id);
        return view('maintenance_requests/view', $data);
    }
}
