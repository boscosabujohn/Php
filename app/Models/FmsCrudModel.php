<?php
namespace App\Models;

use CodeIgniter\Model;

class FmsCrudModel extends Model
{
    private $procedureCache = [];
    
    public function callCrudProcedure(string $table, string $operation, array $params = [])
    {
        try {
            $db = \Config\Database::connect();
            $procedure = 'fms_' . $table . '_' . $operation;
            
            // Get procedure parameters dynamically
            $procParams = $this->getProcedureParameters($procedure);
            if (empty($procParams)) {
                return $this->fallbackOperation($table, $operation, $params);
            }
            
            // Map input data to procedure parameters
            $mappedParams = $this->mapParametersToValues($procParams, $params, $operation);
            
            // Log for debugging
            log_message('info', "Calling stored procedure: {$procedure}");
            log_message('info', "Procedure params: " . json_encode($procParams));
            log_message('info', "Mapped values: " . json_encode($mappedParams));
            
            // Build and execute the procedure call
            $placeholders = implode(',', array_fill(0, count($mappedParams), '?'));
            $sql = "CALL {$procedure}({$placeholders})";
            
            $query = $db->query($sql, $mappedParams);
            
            if (!$query) {
                throw new \Exception("Query failed: " . $db->error());
            }
            
            if ($operation === 'filter') {
                $result = $query->getResultArray();
                return [
                    'success' => true,
                    'data' => $result,
                    'message' => 'Data retrieved successfully'
                ];
            } else {
                // For create, update, delete operations
                $affectedRows = $db->affectedRows();
                $insertId = ($operation === 'create') ? $db->insertID() : null;
                
                return [
                    'success' => $affectedRows > 0,
                    'data' => $insertId ? ['id' => $insertId] : [],
                    'message' => $affectedRows > 0 ? 'Operation completed successfully' : 'No records affected',
                    'affected_rows' => $affectedRows
                ];
            }
        } catch (\Exception $e) {
            log_message('error', "Stored procedure error: " . $e->getMessage());
            
            // If stored procedure doesn't exist, try fallback direct table operations
            if (strpos($e->getMessage(), "doesn't exist") !== false || 
                strpos($e->getMessage(), "PROCEDURE") !== false) {
                return $this->fallbackOperation($table, $operation, $params);
            }
            
            return [
                'success' => false,
                'data' => [],
                'message' => 'Database operation failed: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Get stored procedure parameters by introspecting the database
     */
    private function getProcedureParameters(string $procedure): array
    {
        // Check cache first
        if (isset($this->procedureCache[$procedure])) {
            return $this->procedureCache[$procedure];
        }
        
        try {
            $db = \Config\Database::connect();
            
            // Get procedure definition
            $query = $db->query("SHOW CREATE PROCEDURE `{$procedure}`");
            $result = $query->getRowArray();
            
            if (!$result) {
                log_message('warning', "Procedure {$procedure} not found");
                return [];
            }
            
            // Extract parameters from procedure definition
            $createProc = $result['Create Procedure'];
            
            // Match parameters between parentheses before BEGIN
            if (preg_match('/\((.*?)\)\s*BEGIN/s', $createProc, $matches)) {
                $paramString = trim($matches[1]);
                
                if (empty($paramString)) {
                    return [];
                }
                
                // Parse individual parameters
                $params = [];
                $paramLines = explode(',', $paramString);
                
                foreach ($paramLines as $line) {
                    $line = trim($line);
                    if (preg_match('/IN\s+(\w+)\s+/', $line, $paramMatch)) {
                        $params[] = $paramMatch[1];
                    }
                }
                
                // Cache the result
                $this->procedureCache[$procedure] = $params;
                return $params;
            }
            
            return [];
        } catch (\Exception $e) {
            log_message('error', "Error getting procedure parameters for {$procedure}: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Map input data to procedure parameters with automatic handling of standard fields
     */
    private function mapParametersToValues(array $procParams, array $inputData, string $operation): array
    {
        $mappedValues = [];
        $currentUserId = session()->get('user_id') ?? 1; // Default user ID
        $currentTimestamp = date('Y-m-d H:i:s');
        
        foreach ($procParams as $param) {
            $value = null;
            
            // Remove 'p_' prefix to get the actual field name
            $fieldName = (strpos($param, 'p_') === 0) ? substr($param, 2) : $param;
            
            // Handle standard fields automatically
            switch ($fieldName) {
                case 'created_by':
                    $value = ($operation === 'create') ? $currentUserId : ($inputData['created_by'] ?? $currentUserId);
                    break;
                    
                case 'updated_by':
                    $value = $currentUserId;
                    break;
                    
                case 'created_at':
                    $value = ($operation === 'create') ? $currentTimestamp : ($inputData['created_at'] ?? $currentTimestamp);
                    break;
                    
                case 'updated_at':
                    $value = $currentTimestamp;
                    break;
                    
                default:
                    // For regular fields, use the input data
                    $value = $inputData[$fieldName] ?? null;
                    
                    // For filter operations, allow null values
                    if ($operation === 'filter' && $value === '') {
                        $value = null;
                    }
                    
                    break;
            }
            
            $mappedValues[] = $value;
        }
        
        return $mappedValues;
    }
    
    /**
     * Fallback operation using direct table queries when stored procedures are not available
     */
    private function fallbackOperation(string $table, string $operation, array $params)
    {
        try {
            $db = \Config\Database::connect();
            $builder = $db->table('fms_' . $table);
            $currentUserId = session()->get('user_id') ?? 1;
            $currentTimestamp = date('Y-m-d H:i:s');
            
            switch ($operation) {
                case 'create':
                    // Add standard fields for create
                    $params['created_by'] = $params['created_by'] ?? $currentUserId;
                    $params['updated_by'] = $params['updated_by'] ?? $currentUserId;
                    $params['created_at'] = $currentTimestamp;
                    $params['updated_at'] = $currentTimestamp;
                    
                    $result = $builder->insert($params);
                    return [
                        'success' => $result,
                        'data' => $result ? ['id' => $db->insertID()] : [],
                        'message' => $result ? 'Record created successfully' : 'Failed to create record'
                    ];
                    
                case 'update':
                    if (isset($params['id'])) {
                        $id = $params['id'];
                        unset($params['id']);
                        
                        // Add standard fields for update
                        $params['updated_by'] = $currentUserId;
                        $params['updated_at'] = $currentTimestamp;
                        
                        $result = $builder->where('id', $id)->update($params);
                        return [
                            'success' => $result,
                            'data' => [],
                            'message' => $result ? 'Record updated successfully' : 'Failed to update record'
                        ];
                    }
                    break;
                    
                case 'delete':
                    if (isset($params['id'])) {
                        $result = $builder->where('id', $params['id'])->delete();
                        return [
                            'success' => $result,
                            'data' => [],
                            'message' => $result ? 'Record deleted successfully' : 'Failed to delete record'
                        ];
                    }
                    break;
                    
                case 'filter':
                    // Apply filters dynamically
                    foreach ($params as $key => $value) {
                        if (!empty($value) && $value !== null) {
                            // Remove 'p_' prefix if present
                            $field = (strpos($key, 'p_') === 0) ? substr($key, 2) : $key;
                            
                            // Use LIKE for string fields, exact match for others
                            if (is_string($value)) {
                                $builder->like($field, $value);
                            } else {
                                $builder->where($field, $value);
                            }
                        }
                    }
                    
                    $result = $builder->get()->getResultArray();
                    return [
                        'success' => true,
                        'data' => $result,
                        'message' => 'Data retrieved successfully'
                    ];
            }
            
            return [
                'success' => false,
                'data' => [],
                'message' => 'Invalid operation or missing parameters'
            ];
            
        } catch (\Exception $e) {
            log_message('error', "Fallback operation error: " . $e->getMessage());
            return [
                'success' => false,
                'data' => [],
                'message' => 'Operation failed: ' . $e->getMessage()
            ];
        }
    }
}
