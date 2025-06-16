<?php
namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\TenantModel;

class Tenant extends Controller
{
    public function index()
    {
        // Guest registration page
        return view('tenant_registration');
    }
    
    public function register()
    {
        // Handle tenant registration
        if ($this->request->getMethod() === 'POST') {
            $tenantModel = new TenantModel();
            
            // Prepare tenant data according to fms_tenants table structure
            $tenantData = [
                'name' => $this->request->getPost('name'),
                'property_id' => $this->request->getPost('property_id'),
                'flat_office_number' => $this->request->getPost('flat_office_no'),
                'email' => $this->request->getPost('email'),
                'address' => $this->request->getPost('address'),
                'created_by' => null, // Guest registration
                'updated_by' => null,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
            
            try {
                // Create tenant record
                $result = $tenantModel->createTenant($tenantData);
                
                if ($result) {
                    // Get the created tenant to get the ID
                    $tenant = $tenantModel->where('email', $tenantData['email'])
                                        ->where('property_id', $tenantData['property_id'])
                                        ->first();
                    
                    if ($tenant) {
                        // Create primary contact record
                        $primaryPhone = $this->request->getPost('phone');
                        if ($primaryPhone) {
                            $tenantModel->createTenantContact([
                                'tenant_id' => $tenant['id'],
                                'contact_name' => $tenantData['name'],
                                'phone_number' => $primaryPhone,
                                'is_primary' => 1
                            ]);
                        }
                        
                        // Create emergency contact if provided
                        $emergencyContact = $this->request->getPost('emergency_contact');
                        $emergencyPhone = $this->request->getPost('emergency_phone');
                        if ($emergencyContact && $emergencyPhone) {
                            $tenantModel->createTenantContact([
                                'tenant_id' => $tenant['id'],
                                'contact_name' => $emergencyContact,
                                'phone_number' => $emergencyPhone,
                                'is_primary' => 0
                            ]);
                        }
                        
                        // Create session for the new tenant
                        session()->set([
                            'tenant_id' => $tenant['id'],
                            'tenant_name' => $tenant['name'],
                            'tenant_email' => $tenant['email'],
                            'property_id' => $tenant['property_id'],
                            'flat_office_number' => $tenant['flat_office_number'],
                            'is_tenant' => true,
                            'is_guest' => true
                        ]);
                        
                        return redirect()->to('/tenant_portal')->with('success', 'Registration successful! Welcome to the tenant portal.');
                    }
                }
                
                return redirect()->back()->with('error', 'Registration failed. Please try again.');
                
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Registration failed: ' . $e->getMessage());
            }
        }
        
        return view('tenant_registration');
    }
    
    public function guestLogin()
    {
        // Guest login page
        return view('guest_login');
    }
    
    public function processGuestLogin()
    {
        // Handle guest login
        if ($this->request->getMethod() === 'POST') {
            $email = $this->request->getPost('email');
            $phone = $this->request->getPost('phone');
            
            $tenantModel = new TenantModel();
            
            // Find tenant by email and verify phone through contact records
            $tenant = $tenantModel->getTenantByEmailAndPhone($email, $phone);
            
            if ($tenant) {
                // Create session for existing tenant
                session()->set([
                    'tenant_id' => $tenant['id'],
                    'tenant_name' => $tenant['name'],
                    'tenant_email' => $tenant['email'],
                    'property_id' => $tenant['property_id'],
                    'flat_office_number' => $tenant['flat_office_number'],
                    'is_tenant' => true,
                    'is_guest' => true
                ]);
                
                return redirect()->to('/tenant_portal')->with('success', 'Welcome back, ' . $tenant['name'] . '!');
            } else {
                return redirect()->back()->with('error', 'Invalid email or phone number. Please check your details or register as a new tenant.');
            }
        }
        
        return view('guest_login');
    }
    
    public function logout()
    {
        // Tenant logout
        session()->destroy();
        return redirect()->to('/tenant/guest-login')->with('success', 'You have been logged out successfully.');
    }
    
    public function management()
    {
        // Admin/Staff tenant management page
        return view('tenants_management');
    }
    
    public function getProperties()
    {
        // API endpoint to get properties for dropdown
        $tenantModel = new TenantModel();
        $searchTerm = $this->request->getGet('search');
        
        $properties = $tenantModel->getPropertiesForSelect($searchTerm);
        
        return $this->response->setJSON($properties);
    }
}
