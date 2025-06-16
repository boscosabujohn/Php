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
        $session->set([
            'user_id' => 1,
            'user_name' => 'Test User',
            'roles' => ['Admin', 'Supervisor', 'Technician'],
            'status' => 'Active',
            'language' => 'en',
        ]);
        
        return redirect()->to('/test_all_forms')->with('message', 'Mock session created for testing');
    }
}
