<?php

namespace App\Controllers\Auth;

use App\Core\Auth;
use App\Core\Session;
use App\Core\View;
use App\Repositories\UserRepository;

class RegisteredUserController
{
    public function create(): string
    {
        return View::render('auth.register');
    }

    public function store(): void
    {
        $name = trim($_POST['name'] ?? '');
        $email = trim(strtolower($_POST['email'] ?? ''));
        $password = $_POST['password'] ?? '';
        $passwordConfirmation = $_POST['password_confirmation'] ?? '';

        $errors = [];

        if ($name === '' || mb_strlen($name) > 255) {
            $errors['name'] = ['Tên không được để trống và không quá 255 ký tự.'];
        }
        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = ['Email không hợp lệ.'];
        }
        if (mb_strlen($password) < 8) {
            $errors['password'] = ['Mật khẩu phải có ít nhất 8 ký tự.'];
        }
        if ($password !== $passwordConfirmation) {
            $errors['password'] = ['Xác nhận mật khẩu không khớp.'];
        }

        // Check unique email
        $userRepo = new UserRepository();
        if ($userRepo->findByEmail($email)) {
            $errors['email'] = ['Email này đã được sử dụng.'];
        }

        if (!empty($errors)) {
            Session::flash('errors', $errors);
            Session::flash('_old', $_POST);
            redirect('/register');
            return;
        }

        $userId = $userRepo->create([
            'name' => $name,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'role' => 'user',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        $user = $userRepo->findById($userId);
        Auth::login($user);

        redirect('/');
    }
}
