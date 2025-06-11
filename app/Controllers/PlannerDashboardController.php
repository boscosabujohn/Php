<?php
namespace App\Controllers;

use CodeIgniter\Controller;

class PlannerDashboardController extends Controller
{
    public function index()
    {
        return view('plannerDashboard');
    }
}
