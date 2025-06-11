<?php
namespace App\Controllers;

use CodeIgniter\Controller;

class TechnicianFeedbackController extends Controller
{
    public function index($id = null)
    {
        return view('technician_feedback');
    }
}
