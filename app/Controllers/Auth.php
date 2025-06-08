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

    // public function verify()
    // {
    //     helper(['form', 'url', 'cookie']);
    //     $session = session();
    //     $lang = $this->request->getPost('language') ?? 'en';
    //     $session->set('language', $lang);
    //     $email = $this->request->getPost('email');
    //     $password = $this->request->getPost('password');
    //     $userModel = new UserModel();
    //     $user = $userModel->getUserByEmail($email);
    //     if (!$user) {
    //         $session->setFlashdata('error', $lang === 'ar' ? 'البريد الإلكتروني غير صحيح' : 'Invalid Email');
    //         log_message('debug', 'Login: No user found for email ' . $email);
    //         return redirect()->to('/login');
    //     }
    //     // Display the supplied email and DB result for debugging
    //     // $debugInfo = '<b>Email supplied:</b> ' . esc($email) . '<br><b>DB result:</b><pre>' . print_r($user, true) . '</pre>';
    //     // $debugInfo .= '<br><b>Press any key to continue to password check...</b>';
    //     // echo $debugInfo;
    //     // Wait for key press (only works in CLI, not in browser)
    //     // if (php_sapi_name() === 'cli') {
    //     //     fgets(STDIN);
    //     // } else {
    //     //     echo '<script>window.addEventListener("keydown",function(){window.location.reload();},{once:true});</script>';
    //     //     exit;
    //     // }
    //     // // Show password hash and result for debugging
    //     // If DB result is an array with 'password' key, use it. If nested, use $user['user']['password']
    //     if (isset($user['password'])) {
    //         $passwordHash = $user['password'];
    //     } elseif (isset($user['user']['password'])) {
    //         $passwordHash = $user['user']['password'];
    //     } else {
    //         $passwordHash = '';
    //     }
    //     $isHashValid = !empty($passwordHash) && strlen($passwordHash) > 20;
    //     if ($isHashValid && password_verify($password, $passwordHash)) {
    //         $session->set([
    //             'user_id' => $user['id'],
    //             'user_name' => $user['name'],
    //             'roles' => isset($user['role_name']) ? [$user['role_name']] : [],
    //             'status' => $user['status_name'] ?? null,
    //             'language' => $lang,
    //         ]);
    //         // Flush output buffer before echoing HTML/JS
    //         if (ob_get_level()) ob_end_clean();
    //         header('Content-Type: text/html; charset=utf-8');
    //         echo "<div class='alert alert-success' style='background:var(--success);color:var(--success-foreground);border:none;text-align:center;margin-top:2em;'>Login successful. Redirecting…</div>";
    //         echo "<script>setTimeout(function(){ window.location.href='/dashboard.php'; }, 1200);</script>";
    //         exit;
    //     }
    //     $session->setFlashdata('error', $lang === 'ar' ? 'البريد الإلكتروني أو كلمة المرور غير صحيحة' : 'Invalid email or password');
    //     return redirect()->to('/login');
    // }
public function verify()
{
    helper(['form', 'url', 'cookie']);
    $session = session();
    $lang = $this->request->getPost('language') ?? 'en';
    $session->set('language', $lang);
    
    $email = $this->request->getPost('email');
    $password = $this->request->getPost('password');
    $userModel = new UserModel();
    $userArray = $userModel->getUserByEmail($email); // Renamed to userArray for clarity

    if (!$userArray || !isset($userArray['user'])) { // Check if userArray and user key exist
        $session->setFlashdata('error', $lang === 'ar' ? 'البريد الإلكتروني غير صحيح' : 'Invalid Email');
        return redirect()->to('/login');
    }

    $user = $userArray['user']; // Get the actual user data

    $passwordHash = $user['password'] ?? ''; // Access password from the user data
    
    if (!empty($passwordHash) && password_verify($password, $passwordHash)) {
        $session->set([
            'user_id' => $user['id'], // Access id from user data
            'user_name' => $user['name'], // Access name from user data
            'roles' => isset($user['role_name']) ? [$user['role_name']] : [], // Access role_name
            'status' => $user['status_name'] ?? null, // Access status_name
            'language' => $lang,
        ]);
        
        // Direct redirect to adminDashboard.php
        return redirect()->to('/tenant');
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
            $userArray = $userModel->getUserByEmail($email); // Renamed for clarity

            if ($userArray && isset($userArray['user']) && !empty($newPassword)) {
                $user = $userArray['user']; // Get actual user data
                if ($newPassword !== $confirmPassword) {
                    $session->setFlashdata('error', $lang === 'ar' ? 'كلمتا المرور غير متطابقتين' : 'Passwords do not match');
                } else {
                    $db = \Config\Database::connect();
                    // Use set() and where() then update() for CodeIgniter 4 best practice
                    $builder = $db->table('fms_users');
                    $builder->set('password', password_hash($newPassword, PASSWORD_DEFAULT));
                    $builder->where('id', $user['id']); // Access id from user data
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
