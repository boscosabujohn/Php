<?php
namespace App\Controllers;

use CodeIgniter\Controller;

class RolePermissionsController extends Controller
{
    public function index()
    {
        return view('role_permissions_management');
    }
}
