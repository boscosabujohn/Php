<?php
namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\FmsCrudModel;

class FmsCrudController extends ResourceController
{
    protected $modelName = 'App\Models\FmsCrudModel';
    protected $format = 'json';

    public function create()
    {
        try {
            $input = $this->request->getJSON(true) ?? $this->request->getPost();
            $table = $input['table'] ?? '';
            $data = $input['data'] ?? [];
            
            if (empty($table) || empty($data)) {
                return $this->respond([
                    'success' => false,
                    'message' => 'Table name and data are required'
                ], 400);
            }
            
            $model = new FmsCrudModel();
            $result = $model->callCrudProcedure($table, 'create', $data);
            
            $statusCode = $result['success'] ? 200 : 400;
            return $this->respond($result, $statusCode);
            
        } catch (\Exception $e) {
            log_message('error', 'FmsCrudController::create() error: ' . $e->getMessage());
            return $this->respond([
                'success' => false,
                'message' => 'Internal server error'
            ], 500);
        }
    }

    public function update($id = null)
    {
        try {
            $input = $this->request->getJSON(true) ?? $this->request->getPost();
            $table = $input['table'] ?? '';
            $data = $input['data'] ?? [];
            
            if (empty($table) || empty($data)) {
                return $this->respond([
                    'success' => false,
                    'message' => 'Table name and data are required'
                ], 400);
            }
            
            $model = new FmsCrudModel();
            $result = $model->callCrudProcedure($table, 'update', $data);
            
            $statusCode = $result['success'] ? 200 : 400;
            return $this->respond($result, $statusCode);
            
        } catch (\Exception $e) {
            log_message('error', 'FmsCrudController::update() error: ' . $e->getMessage());
            return $this->respond([
                'success' => false,
                'message' => 'Internal server error'
            ], 500);
        }
    }

    public function delete($id = null)
    {
        try {
            $input = $this->request->getJSON(true) ?? $this->request->getPost();
            $table = $input['table'] ?? '';
            $data = $input['data'] ?? [];
            
            if (empty($table) || empty($data)) {
                return $this->respond([
                    'success' => false,
                    'message' => 'Table name and data are required'
                ], 400);
            }
            
            $model = new FmsCrudModel();
            $result = $model->callCrudProcedure($table, 'delete', $data);
            
            $statusCode = $result['success'] ? 200 : 400;
            return $this->respond($result, $statusCode);
            
        } catch (\Exception $e) {
            log_message('error', 'FmsCrudController::delete() error: ' . $e->getMessage());
            return $this->respond([
                'success' => false,
                'message' => 'Internal server error'
            ], 500);
        }
    }

    public function filter()
    {
        try {
            $input = $this->request->getJSON(true) ?? $this->request->getPost();
            $table = $input['table'] ?? '';
            $filters = $input['filters'] ?? [];
            
            log_message('info', 'FmsCrudController::filter() - Table: ' . $table . ', Filters: ' . json_encode($filters));
            
            if (empty($table)) {
                return $this->respond([
                    'success' => false,
                    'message' => 'Table name is required'
                ], 400);
            }
            
            $model = new FmsCrudModel();
            $result = $model->callCrudProcedure($table, 'filter', $filters);
            
            return $this->respond($result);
            
        } catch (\Exception $e) {
            log_message('error', 'FmsCrudController::filter() error: ' . $e->getMessage());
            return $this->respond([
                'success' => false,
                'message' => 'Internal server error'
            ], 500);
        }
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
