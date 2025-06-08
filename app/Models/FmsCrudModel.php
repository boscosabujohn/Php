<?php
namespace App\Models;

use CodeIgniter\Model;

class FmsCrudModel extends Model
{
    public function callCrudProcedure(string $table, string $operation, array $params = [])
    {
        $db = \Config\Database::connect();
        $procedure = $table . '_' . $operation;
        $placeholders = implode(',', array_fill(0, count($params), '?'));
        $sql = "CALL $procedure($placeholders)";
        $query = $db->query($sql, array_values($params));
        if ($operation === 'filter') {
            return $query->getResultArray();
        }
        return $query->getResult();
    }
}
