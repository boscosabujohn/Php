<?php
namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;

class ResetPassword extends Controller
{
    public function index()
    {
        $session = session();
        $lang = $session->get('language') ?? 'en';
        $data = [
            'lang' => $lang,
            'message' => $session->getFlashdata('message'),
        ];
        return view('reset_password', $data);
    }

    public function send()
    {
        $session = session();
        $lang = $session->get('language') ?? 'en';
        $email = $this->request->getPost('email');
        $userModel = new UserModel();
        $user = $userModel->getUserByEmail($email);
        if ($user) {
            // Dummy: In real app, send email with reset link/code
            $msg = $lang === 'ar' ? 'تم إرسال رابط إعادة التعيين إلى بريدك الإلكتروني.' : 'A reset link has been sent to your email.';
        } else {
            $msg = $lang === 'ar' ? 'البريد الإلكتروني غير موجود.' : 'Email not found.';
        }
        $session->setFlashdata('message', $msg);
        return redirect()->to('/reset-password');
    }
}
