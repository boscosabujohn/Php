<?php
namespace App\Models;

use CodeIgniter\Model;

class FmsOtherModel extends Model
{
    public function callProcedure(string $procedure, array $params = [])
    {
        $db = \Config\Database::connect();
        $placeholders = implode(',', array_fill(0, count($params), '?'));
        $sql = "CALL $procedure($placeholders)";
        $query = $db->query($sql, array_values($params));
        return $query->getResultArray();
    }
    public function getUserByKey($inputKey)
    {
        $db = \Config\Database::connect();
        $sql = "CALL fms_users_get_by_key(?)";
        $query = $db->query($sql, [$inputKey]);
        return $query->getResultArray();
    }

    public function updateUserPasswordByKey($inputKey, $hashedPassword)
    {
        $db = \Config\Database::connect();
        $sql = "CALL fms_users_update_password_by_key(?, ?)";
        $query = $db->query($sql, [$inputKey, $hashedPassword]);
        return $db->affectedRows();
    }
}
