<?php

namespace App\Middleware;

use App\Core\Auth;
use App\Core\Session;

class AuthMiddleware
{
    public function handle(): mixed
    {
        if (!Auth::check()) {
            Session::flash('error', 'Vui lòng đăng nhập để tiếp tục.');
            header('Location: /login');
            exit;
        }
        return null;
    }
}
