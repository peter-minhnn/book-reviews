<?php

namespace App\Middleware;

use App\Core\Auth;
use App\Core\Session;

class AdminMiddleware
{
    public function handle(): mixed
    {
        if (!Auth::check() || !Auth::isAdmin()) {
            Session::flash('error', 'Bạn không có quyền truy cập trang này.');
            header('Location: /');
            exit;
        }
        return null;
    }
}
