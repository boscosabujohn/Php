<?php
namespace App\Controllers;

use CodeIgniter\Controller;

class Dashboard extends Controller
{
    public function index()
    {
        $session = session();
        if (!$session->has('user_id')) {
            return redirect()->to('/login');
        }
        $data = [
            'user_name' => $session->get('user_name'),
            'roles' => $session->get('roles'),
            'lang' => $session->get('language') ?? 'en',
        ];
        return view('dashboard', $data);
    }
}
