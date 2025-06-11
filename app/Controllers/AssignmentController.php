<?php
namespace App\Controllers;

use CodeIgniter\Controller;

class AssignmentController extends Controller
{
    public function index($id = null)
    {
        return view('assignment');
    }
}
