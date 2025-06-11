<?php
namespace App\Controllers;

use CodeIgniter\Controller;

class TenantPortalController extends Controller
{
    public function index()
    {
        return view('tenant_portal');
    }
}
