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

    public function adminDashboard()
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
        return view('adminDashboard', $data);
    }

    public function supervisorDashboard()
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
        return view('supervisorDashboard', $data);
    }

    public function technicianDashboard()
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
        return view('technicianDashboard', $data);
    }
}
