<?php
namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;
use CodeIgniter\HTTP\RedirectResponse;

class Auth extends Controller
{
    public function login()
    {
        helper(['form', 'url', 'cookie']);
        $session = session();
        $lang = $this->request->getPost('language') ?? $session->get('language') ?? 'en';
        $session->set('language', $lang);
        $data = [
            'lang' => $lang,
            'error' => $session->getFlashdata('error'),
        ];
        return view('login', $data);
    }

    public function verify()
    {
        helper(['form', 'url', 'cookie']);
        $session = session();
        $lang = $this->request->getPost('language') ?? 'en';
        $session->set('language', $lang);
        
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $userModel = new UserModel();
        $userArray = $userModel->getUserByEmail($email);

        if (!$userArray || !isset($userArray['user'])) {
            $session->setFlashdata('error', $lang === 'ar' ? 'البريد الإلكتروني غير صحيح' : 'Invalid Email');
            return redirect()->to('/login');
        }

        $user = $userArray['user'];
        $passwordHash = $user['password'] ?? '';
        
        if (!empty($passwordHash) && password_verify($password, $passwordHash)) {
            $session->set([
                'user_id' => $user['id'],
                'user_name' => $user['name'],
                'roles' => isset($user['role_name']) ? [$user['role_name']] : [],
                'status' => $user['status_name'] ?? null,
                'language' => $lang,
            ]);
            // Use route, not .php file, for redirect
            return redirect()->to(base_url('tenants_management'));
        }

        $session->setFlashdata('error', $lang === 'ar' ? 'البريد الإلكتروني أو كلمة المرور غير صحيحة' : 'Invalid email or password');
        return redirect()->to('/login');
    }
    public function guest()
    {
        return redirect()->to('/tenant');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }

    public function resetPassword()
    {
        helper(['form', 'url']);
        $session = session();
        $lang = $session->get('language') ?? 'en';
        if ($this->request->getMethod() === 'post') {
            $email = $this->request->getPost('email');
            $newPassword = $this->request->getPost('password');
            $confirmPassword = $this->request->getPost('confirm_password');
            $userModel = new UserModel();
            $userArray = $userModel->getUserByEmail($email); 

            if ($userArray && isset($userArray['user']) && !empty($newPassword)) {
                $user = $userArray['user']; 
                if ($newPassword !== $confirmPassword) {
                    $session->setFlashdata('error', $lang === 'ar' ? 'كلمتا المرور غير متطابقتين' : 'Passwords do not match');
                } else {
                    $db = \Config\Database::connect();
                    // Use set() and where() then update() for CodeIgniter 4 best practice
                    $builder = $db->table('fms_users');
                    $builder->set('password', password_hash($newPassword, PASSWORD_DEFAULT));
                    $builder->where('id', $user['id']); 
                    $updated = $builder->update();
                    if ($updated) {
                        echo "<script>alert('Password updated successfully');window.location.href='/login';</script>";
                        exit;
                    } else {
                        $session->setFlashdata('error', $lang === 'ar' ? 'فشل تحديث كلمة المرور' : 'Failed to update password');
                    }
                }
            } else {
                $session->setFlashdata('error', $lang === 'ar' ? 'البريد الإلكتروني غير صحيح أو كلمة المرور فارغة' : 'Invalid email or empty password');
            }
        }
        return view('reset_password', ['lang' => $lang, 'error' => $session->getFlashdata('error')]);
    }
}
