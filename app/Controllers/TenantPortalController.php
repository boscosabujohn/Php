<?php
namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\TenantModel;

class TenantPortalController extends Controller
{
    public function index()
    {
        // Check if user is logged in as tenant
        if (!session()->get('is_tenant')) {
            return redirect()->to('/tenant/guest-login')->with('error', 'Please log in to access the tenant portal.');
        }
        
        $tenantId = session()->get('tenant_id');
        $tenantName = session()->get('tenant_name');
        $tenantEmail = session()->get('tenant_email');
        $propertyId = session()->get('property_id');
        $flatOfficeNumber = session()->get('flat_office_number');
        
        // Get tenant details with contacts
        $tenantModel = new TenantModel();
        $tenantData = $tenantModel->getTenantWithContacts($tenantId);
        
        $data = [
            'tenant_id' => $tenantId,
            'tenant_name' => $tenantName,
            'tenant_email' => $tenantEmail,
            'property_id' => $propertyId,
            'flat_office_number' => $flatOfficeNumber,
            'tenant_data' => $tenantData,
            'is_guest' => session()->get('is_guest', false)
        ];
        
        return view('tenant_portal', $data);
    }
    
    public function profile()
    {
        // Tenant profile management
        if (!session()->get('is_tenant')) {
            return redirect()->to('/tenant/guest-login');
        }
        
        return view('tenant_profile');
    }
    
    public function maintenanceRequests()
    {
        // View maintenance requests
        if (!session()->get('is_tenant')) {
            return redirect()->to('/tenant/guest-login');
        }
        
        return view('tenant_maintenance_requests');
    }
}
