<?php
namespace App\Controllers;

use CodeIgniter\Controller;

class SupervisorReviewController extends Controller
{
    public function index($id = null)
    {
        return view('supervisor_review');
    }
}
