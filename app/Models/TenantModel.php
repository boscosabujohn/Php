<?php

namespace App\Models;

use CodeIgniter\Model;

class TenantModel extends Model
{
    protected $table = 'fms_tenants';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'property_id', 'flat_office_number', 'email', 'address', 'created_by', 'updated_by'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $db;

    public function __construct()
    {
        parent::__construct();
        $this->db = \Config\Database::connect();
    }

    /**
     * Create a new tenant using stored procedure if available, otherwise use direct insert
     */
    public function createTenant(array $data): bool
    {
        try {
            // Try using stored procedure first
            $sql = 'CALL fms_tenants_create(?, ?, ?, ?, ?, ?)';
            $this->db->query($sql, [
                $data['name'],
                $data['property_id'],
                $data['flat_office_number'],
                $data['email'],
                $data['address'],
                $data['created_by'] ?? null
            ]);
            return $this->db->affectedRows() > 0;
        } catch (\Throwable $e) {
            // Fallback to direct insert if stored procedure doesn't exist
            log_message('info', '[TenantModel] Stored procedure not available, using direct insert: ' . $e->getMessage());
            return $this->insert($data) !== false;
        }
    }

    /**
     * Create a tenant contact record
     */
    public function createTenantContact(array $contactData): bool
    {
        try {
            $builder = $this->db->table('fms_tenant_contacts');
            return $builder->insert($contactData);
        } catch (\Throwable $e) {
            log_message('error', '[TenantModel] Error creating tenant contact: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get tenant by email and phone verification
     */
    public function getTenantByEmailAndPhone(string $email, string $phone): ?array
    {
        try {
            $sql = "
                SELECT t.*, c.phone_number
                FROM fms_tenants t
                INNER JOIN fms_tenant_contacts c ON t.id = c.tenant_id
                WHERE t.email = ? AND c.phone_number = ? AND c.is_primary = 1
                LIMIT 1
            ";
            
            $query = $this->db->query($sql, [$email, $phone]);
            $result = $query->getRowArray();
            
            return $result ?: null;
        } catch (\Throwable $e) {
            log_message('error', '[TenantModel] Error getting tenant by email and phone: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get tenant with contacts
     */
    public function getTenantWithContacts(int $tenantId): ?array
    {
        try {
            // Get tenant info
            $tenant = $this->find($tenantId);
            if (!$tenant) {
                return null;
            }
            
            // Convert to array if needed
            $tenantArray = is_array($tenant) ? $tenant : $tenant->toArray();
            
            // Get contacts
            $contacts = $this->db->table('fms_tenant_contacts')
                               ->where('tenant_id', $tenantId)
                               ->get()
                               ->getResultArray();
            
            $tenantArray['contacts'] = $contacts;
            return $tenantArray;
        } catch (\Throwable $e) {
            log_message('error', '[TenantModel] Error getting tenant with contacts: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Update tenant using stored procedure if available
     */
    public function updateTenant(int $id, array $data): bool
    {
        try {
            // Try using stored procedure first
            $sql = 'CALL fms_tenants_update(?, ?, ?, ?, ?, ?, ?)';
            $this->db->query($sql, [
                $id,
                $data['name'],
                $data['property_id'],
                $data['flat_office_number'],
                $data['email'],
                $data['address'],
                $data['updated_by'] ?? null
            ]);
            return $this->db->affectedRows() > 0;
        } catch (\Throwable $e) {
            // Fallback to direct update
            log_message('info', '[TenantModel] Stored procedure not available, using direct update: ' . $e->getMessage());
            return $this->update($id, $data);
        }
    }

    /**
     * Delete tenant using stored procedure if available
     */
    public function deleteTenant(int $id): bool
    {
        try {
            // Try using stored procedure first
            $sql = 'CALL fms_tenants_delete(?)';
            $this->db->query($sql, [$id]);
            return $this->db->affectedRows() > 0;
        } catch (\Throwable $e) {
            // Fallback to direct delete
            log_message('info', '[TenantModel] Stored procedure not available, using direct delete: ' . $e->getMessage());
            return $this->delete($id);
        }
    }

    /**
     * Filter tenants with optional stored procedure
     */
    public function filterTenants(array $filters = []): array
    {
        try {
            // Try using stored procedure first
            $sql = 'CALL fms_tenants_filter(?, ?, ?, ?, ?, ?, ?, ?)';
            $query = $this->db->query($sql, [
                $filters['id'] ?? null,
                $filters['name'] ?? null,
                $filters['property_id'] ?? null,
                $filters['flat_office_number'] ?? null,
                $filters['email'] ?? null,
                $filters['address'] ?? null,
                $filters['building_name'] ?? null,
                $filters['building_number'] ?? null,
            ]);
            return $query->getResultArray();
        } catch (\Throwable $e) {
            // Fallback to manual filtering
            log_message('info', '[TenantModel] Stored procedure not available, using manual filter: ' . $e->getMessage());
            
            $builder = $this->builder();
            
            if (!empty($filters['id'])) {
                $builder->where('id', $filters['id']);
            }
            if (!empty($filters['name'])) {
                $builder->like('name', $filters['name']);
            }
            if (!empty($filters['property_id'])) {
                $builder->where('property_id', $filters['property_id']);
            }
            if (!empty($filters['flat_office_number'])) {
                $builder->like('flat_office_number', $filters['flat_office_number']);
            }
            if (!empty($filters['email'])) {
                $builder->like('email', $filters['email']);
            }
            if (!empty($filters['address'])) {
                $builder->like('address', $filters['address']);
            }
            
            return $builder->get()->getResultArray();
        }
    }

    /**
     * Get properties for select dropdown
     */
    public function getPropertiesForSelect(string $searchTerm = null): array
    {
        try {
            $builder = $this->db->table('fms_properties')
                               ->select('id, name as building_name, building_number');
            
            if (!empty($searchTerm)) {
                $builder->groupStart()
                       ->like('name', $searchTerm)
                       ->orLike('building_number', $searchTerm)
                       ->groupEnd();
            }
            
            return $builder->get()->getResultArray();
        } catch (\Throwable $e) {
            log_message('error', '[TenantModel] Error fetching properties for select: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Check if email is unique
     */
    public function isEmailUnique(string $email, int $excludeTenantId = null): bool
    {
        $builder = $this->where('email', $email);
        if ($excludeTenantId !== null) {
            $builder->where('id !=', $excludeTenantId);
        }
        return $builder->countAllResults() === 0;
    }
}
