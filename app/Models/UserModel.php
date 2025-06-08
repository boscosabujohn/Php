<?php
namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'fms_users';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'email', 'password', 'phone', 'role_id', 'status_id', 'created_by', 'updated_by', 'created_at', 'updated_at'];

    public function getUserByEmail($email)
    {
        $db = \Config\Database::connect();
        try {
            // Check if function is invoked
            log_message('debug', 'getUserByEmail called with email: ' . $email);
            $query = $db->query('CALL fms_users_filter(NULL, NULL, ?, NULL, NULL, NULL, NULL)', [$email]);
            $users = $query->getResultArray();
            log_message('debug', 'getUserByEmail result: ' . print_r($users, true));
            if (count($users) > 0) {
                return [
                    'user' => $users[0],
                    'message' => 'User found for email: ' . $email
                ];
            } else {
                log_message('info', 'No user records fetched for email: ' . $email);
            }
        } catch (\Throwable $e) {
            log_message('error', 'Login DB error: ' . $e->getMessage());
        }
        return null;
    }
    public function login($email, $password)
    {
        $db = \Config\Database::connect();
        $query = $db->query('CALL fms_users_filter(NULL, NULL, NULL, NULL)');
        $users = $query->getResultArray();
        foreach ($users as $user) {
            if ($user['email'] === $email && password_verify($password, $user['password'])) {
                return [
                    'id' => $user['id'],
                    'name' => $user['name'],
                    'roles' => [$user['role_name']],
                    'status' => $user['status_name'],
                    'email' => $user['email'],
                ];
            }
        }
        return null;
    }
}
