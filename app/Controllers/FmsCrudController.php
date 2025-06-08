<?php
namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\FmsCrudModel;

class FmsCrudController extends ResourceController
{
    public function create()
    {
        $input = $this->request->getJSON(true) ?? $this->request->getPost();
        $table = $input['table'] ?? '';
        $data = $input['data'] ?? [];
        $model = new FmsCrudModel();
        $result = $model->callCrudProcedure($table, 'create', $data);
        return $this->respond($result);
    }

    public function update()
    {
        $input = $this->request->getJSON(true) ?? $this->request->getPost();
        $table = $input['table'] ?? '';
        $data = $input['data'] ?? [];
        $model = new FmsCrudModel();
        $result = $model->callCrudProcedure($table, 'update', $data);
        return $this->respond($result);
    }

    public function delete()
    {
        $input = $this->request->getJSON(true) ?? $this->request->getPost();
        $table = $input['table'] ?? '';
        $data = $input['data'] ?? [];
        $model = new FmsCrudModel();
        $result = $model->callCrudProcedure($table, 'delete', $data);
        return $this->respond($result);
    }

    public function filter()
    {
        $input = $this->request->getJSON(true) ?? $this->request->getPost();
        $table = $input['table'] ?? '';
        $filters = $input['filters'] ?? [];
        $model = new FmsCrudModel();
        $result = $model->callCrudProcedure($table, 'filter', $filters);
        return $this->respond($result);
    }
}
