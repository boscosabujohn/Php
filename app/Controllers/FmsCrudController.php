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

    public function update($id = null)
    {
        $input = $this->request->getJSON(true) ?? $this->request->getPost();
        $table = $input['table'] ?? '';
        $data = $input['data'] ?? [];
        $model = new FmsCrudModel();
        $result = $model->callCrudProcedure($table, 'update', $data);
        return $this->respond($result);
    }

    public function delete($id = null)
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

    // Management page methods
    public function propertiesManagement()
    {
        return view('properties_management');
    }

    public function lookupTypesManagement()
    {
        return view('lookup_types_management');
    }

    public function lookupTypesValuesManagement()
    {
        return view('lookup_types_values_management');
    }

    public function teamsManagement()
    {
        return view('teams_management');
    }

    public function technicianSkillsManagement()
    {
        return view('technician_skills_management');
    }
}
