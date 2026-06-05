<?php

namespace App\Controllers\Admin;

use App\Core\View;
use App\Repositories\BookRepository;
use App\Repositories\ReviewRepository;
use App\Repositories\UserRepository;

class DashboardController
{
    public function index(): string
    {
        $bookRepo = new BookRepository();
        $userRepo = new UserRepository();
        $reviewRepo = new ReviewRepository();

        $totalBooks = $bookRepo->count();
        $totalUsers = $userRepo->count();
        $totalReviews = $reviewRepo->count();

        $topRatedBooks = $bookRepo->topRated(5);
        $latestReviews = $reviewRepo->latest(5);

        return View::render('admin.dashboard', compact(
            'totalBooks', 'totalUsers', 'totalReviews',
            'topRatedBooks', 'latestReviews'
        ));
    }
}
