\
<?php

namespace App\\Models;

use CodeIgniter\\Model;

class TenantModel extends Model
{
    protected $db;

    public function __construct()
    {
        parent::__construct();
        $this->db = \\Config\\Database::connect();
    }

    /**
     * Calls the fms_tenants_create stored procedure.
     */
    public function createTenant(array $data): bool
    {
        $sql = 'CALL fms_tenants_create(?, ?, ?, ?, ?, ?)';
        try {
            $this->db->query($sql, [
                $data['name'],
                $data['property_id'],
                $data['flat_office_number'],
                $data['email'],
                $data['address'],
                $data['created_by'] ?? null // Assuming created_by comes from session or is passed
            ]);
            return $this->db->affectedRows() > 0;
        } catch (\\Throwable $e) {
            log_message('error', '[TenantModel] Error creating tenant: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Calls the fms_tenants_update stored procedure.
     */
    public function updateTenant(int $id, array $data): bool
    {
        $sql = 'CALL fms_tenants_update(?, ?, ?, ?, ?, ?, ?)';
        try {
            $this->db->query($sql, [
                $id,
                $data['name'],
                $data['property_id'],
                $data['flat_office_number'],
                $data['email'],
                $data['address'],
                $data['updated_by'] ?? null // Assuming updated_by comes from session or is passed
            ]);
            return $this->db->affectedRows() > 0;
        } catch (\\Throwable $e) {
            log_message('error', '[TenantModel] Error updating tenant ID ' . $id . ': ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Calls the fms_tenants_delete stored procedure.
     */
    public function deleteTenant(int $id): bool
    {
        $sql = 'CALL fms_tenants_delete(?)';
        try {
            $this->db->query($sql, [$id]);
            return $this->db->affectedRows() > 0;
        } catch (\\Throwable $e) {
            log_message('error', '[TenantModel] Error deleting tenant ID ' . $id . ': ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Calls the fms_tenants_filter stored procedure.
     * Parameters should match the stored procedure definition.
     */
    public function filterTenants(array $filters = []): array
    {
        // p_id, p_name, p_property_id, p_flat_office_number, p_email, p_address, p_building_name, p_building_number
        $sql = 'CALL fms_tenants_filter(?, ?, ?, ?, ?, ?, ?, ?)';
        try {
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
        } catch (\\Throwable $e) {
            log_message('error', '[TenantModel] Error filtering tenants: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Fetches properties for the combobox.
     * Assumes a stored procedure fms_properties_filter_for_select exists.
     * This SP should accept a search term and return id, name (building_name), building_number.
     */
    public function getPropertiesForSelect(string $searchTerm = null): array
    {
        // Example: CALL fms_properties_filter_for_select(?);
        // You'll need to create this stored procedure in your database.
        // It should return id, name (as building_name), building_number
        $sql = 'CALL fms_properties_filter_for_select(?)'; // Modify if your SP is different
        try {
            // For now, let's assume a simple properties table direct query if SP is not ready
            // Replace this with your actual SP call
            if (empty($searchTerm)) {
                 $query = $this->db->table('fms_properties')->select('id, name as building_name, building_number')->get();
            } else {
                 $query = $this->db->table('fms_properties')
                                   ->select('id, name as building_name, building_number')
                                   ->like('name', $searchTerm)
                                   ->orLike('building_number', $searchTerm)
                                   ->get();
            }
            return $query->getResultArray();
            
            // Ideal implementation with a stored procedure:
            // $query = $this->db->query($sql, [$searchTerm]);
            // return $query->getResultArray();

        } catch (\\Throwable $e) {
            log_message('error', '[TenantModel] Error fetching properties for select: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Checks if an email already exists for a different tenant.
     * Returns true if email exists for another tenant, false otherwise.
     */
    public function isEmailUnique(string $email, int $excludeTenantId = null): bool
    {
        $builder = $this->db->table('fms_tenants')->where('email', $email);
        if ($excludeTenantId !== null) {
            $builder->where('id !=', $excludeTenantId);
        }
        return $builder->countAllResults() === 0;
    }
}
