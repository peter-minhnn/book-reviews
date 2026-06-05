<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Session;
use App\Core\View;
use App\Repositories\UserRepository;

class ProfileController
{
    public function edit(): string
    {
        $user = Auth::user();
        if (!$user) {
            redirect('/login');
            exit;
        }

        return View::render('profile.edit', ['user' => $user]);
    }

    public function update(): void
    {
        $user = Auth::user();
        if (!$user) {
            redirect('/login');
            return;
        }

        $name = trim($_POST['name'] ?? '');
        $email = trim(strtolower($_POST['email'] ?? ''));

        $errors = [];
        if ($name === '' || mb_strlen($name) > 255) {
            $errors['name'] = ['Tên không được để trống và không quá 255 ký tự.'];
        }
        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = ['Email không hợp lệ.'];
        }

        // Check unique email excluding current user
        $userRepo = new UserRepository();
        $existing = $userRepo->findByEmail($email);
        if ($existing && $existing['id'] !== $user['id']) {
            $errors['email'] = ['Email này đã được sử dụng.'];
        }

        if (!empty($errors)) {
            Session::flash('errors', $errors);
            back();
            return;
        }

        $updateData = [
            'name' => $name,
            'email' => $email,
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        if ($email !== $user['email']) {
            $updateData['email_verified_at'] = null;
        }

        $userRepo->update($user['id'], $updateData);

        Session::flash('status', 'profile-updated');
        back();
    }

    public function destroy(): void
    {
        $user = Auth::user();
        if (!$user) {
            redirect('/login');
            return;
        }

        $password = $_POST['password'] ?? '';

        if (!password_verify($password, $user['password'])) {
            Session::flash('errors', ['password' => ['Mật khẩu không chính xác.']]);
            back();
            return;
        }

        $userRepo = new UserRepository();
        Auth::logout();
        $userRepo->delete($user['id']);
        Session::invalidate();

        redirect('/');
    }
}
