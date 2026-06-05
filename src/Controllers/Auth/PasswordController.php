<?php

namespace App\Controllers\Auth;

use App\Core\Auth;
use App\Core\Session;
use App\Repositories\UserRepository;

class PasswordController
{
    public function update(): void
    {
        $user = Auth::user();
        if (!$user) {
            redirect('/login');
            return;
        }

        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['password'] ?? '';
        $passwordConfirmation = $_POST['password_confirmation'] ?? '';

        $errors = [];

        if (!password_verify($currentPassword, $user['password'])) {
            $errors['current_password'] = ['Mật khẩu hiện tại không chính xác.'];
        }
        if (mb_strlen($newPassword) < 8) {
            $errors['password'] = ['Mật khẩu mới phải có ít nhất 8 ký tự.'];
        }
        if ($newPassword !== $passwordConfirmation) {
            $errors['password'] = ['Xác nhận mật khẩu không khớp.'];
        }

        if (!empty($errors)) {
            Session::flash('errors', $errors);
            back();
            return;
        }

        $userRepo = new UserRepository();
        $userRepo->update($user['id'], [
            'password' => password_hash($newPassword, PASSWORD_DEFAULT),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        Session::flash('status', 'password-updated');
        back();
    }
}
