<?php
namespace App\Controllers;

use CodeIgniter\Controller;

class TenantSignatureController extends Controller
{
    public function index($id = null)
    {
        return view('tenant_signature');
    }
}
