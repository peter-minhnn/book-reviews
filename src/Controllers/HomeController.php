<?php

namespace App\Controllers;

use App\Core\View;
use App\Repositories\BookRepository;

class HomeController
{
    public function index(): string
    {
        $bookRepo = new BookRepository();

        $latestBooks = $bookRepo->latest(8);
        $topRatedBooks = $bookRepo->topRated(4);

        return View::render('home', [
            'latestBooks' => $latestBooks,
            'topRatedBooks' => $topRatedBooks,
        ]);
    }
}
