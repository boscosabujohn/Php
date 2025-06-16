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
        
        // More flexible session checking
        $hasSession = $session->has('user_id') || $session->has('logged_in');
        
        if (!$hasSession) {
            return redirect()->to('/test_all_forms')->with('error', 'No active session found. Please click "Create Test Session" first, then try again.');
        }
        
        $data = [
            'user_name' => $session->get('user_name') ?? 'Test User',
            'roles' => $session->get('roles') ?? ['Admin'],
            'lang' => $session->get('language') ?? 'en',
        ];
        return view('adminDashboard', $data);
    }

    public function supervisorDashboard()
    {
        $session = session();
        
        $hasSession = $session->has('user_id') || $session->has('logged_in');
        
        if (!$hasSession) {
            return redirect()->to('/test_all_forms')->with('error', 'No active session found. Please click "Create Test Session" first, then try again.');
        }
        
        $data = [
            'user_name' => $session->get('user_name') ?? 'Test User',
            'roles' => $session->get('roles') ?? ['Supervisor'],
            'lang' => $session->get('language') ?? 'en',
        ];
        return view('supervisorDashboard', $data);
    }

    public function technicianDashboard()
    {
        $session = session();
        
        $hasSession = $session->has('user_id') || $session->has('logged_in');
        
        if (!$hasSession) {
            return redirect()->to('/test_all_forms')->with('error', 'No active session found. Please click "Create Test Session" first, then try again.');
        }
        
        $data = [
            'user_name' => $session->get('user_name') ?? 'Test User',
            'roles' => $session->get('roles') ?? ['Technician'],
            'lang' => $session->get('language') ?? 'en',
        ];
        return view('technicianDashboard', $data);
    }
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
