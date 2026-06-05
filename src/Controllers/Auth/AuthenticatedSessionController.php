<?php

namespace App\Controllers\Auth;

use App\Core\Auth;
use App\Core\Session;
use App\Core\View;
use App\Repositories\UserRepository;

class AuthenticatedSessionController
{
    public function create(): string
    {
        return View::render('auth.login');
    }

    public function store(): void
    {
        $email = trim(strtolower($_POST['email'] ?? ''));
        $password = $_POST['password'] ?? '';

        $userRepo = new UserRepository();
        $user = $userRepo->findByEmail($email);

        if (!$user || !password_verify($password, $user['password'])) {
            Session::flash('errors', ['email' => ['Email hoặc mật khẩu không chính xác.']]);
            Session::flash('_old', ['email' => $email]);
            redirect('/login');
            return;
        }

        // Check "remember me"
        Auth::login($user);

        redirect('/');
    }

    public function destroy(): void
    {
        Auth::logout();
        Session::invalidate();
        redirect('/');
    }
}
