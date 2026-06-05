<?php

namespace App\Middleware;

use App\Core\Auth;

class GuestMiddleware
{
    public function handle(): mixed
    {
        if (Auth::check()) {
            header('Location: /');
            exit;
        }
        return null;
    }
}
