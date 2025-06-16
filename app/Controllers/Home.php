<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        return view('welcome_message');
    }
    
    public function testAllForms(): string
    {
        return view('test_all_forms');
    }

    public function testLogin()
    {
        // Create a mock session for testing dashboard pages
        $session = session();
        
        // Clear any existing session data first
        $session->destroy();
        
        // Start a new session
        $session->start();
        
        $session->set([
            'user_id' => 1,
            'user_name' => 'Test Admin User',
            'roles' => ['Admin', 'Supervisor', 'Technician'],
            'status' => 'Active',
            'language' => 'en',
            'logged_in' => true,
            'login_time' => time()
        ]);
        
        return redirect()->to('/test_all_forms')->with('success', 'Mock session created successfully! You can now access all dashboard pages.');
    }
    
    public function checkSession()
    {
        // Debug endpoint to check session
        $session = session();
        $data = [
            'has_user_id' => $session->has('user_id'),
            'session_data' => $session->get(),
            'session_id' => session_id()
        ];
        
        return $this->response->setJSON($data);
    }
}
