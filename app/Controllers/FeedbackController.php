<?php
namespace App\Controllers;

use CodeIgniter\Controller;

class FeedbackController extends Controller
{
    public function index($id = null)
    {
        return view('feedback');
    }
}
