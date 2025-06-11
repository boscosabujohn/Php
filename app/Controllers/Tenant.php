<?php
namespace App\Controllers;

use CodeIgniter\Controller;

class Tenant extends Controller
{
    public function index()
    {
        // Guest registration page placeholder
        return view('tenant');
    }
    
    public function management()
    {
        return view('tenants_management');
    }
}
