<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Session;
use App\Core\View;
use App\Repositories\FavoriteRepository;

class FavoriteController
{
    public function index(): string
    {
        if (!Auth::check()) {
            redirect('/login');
            exit;
        }

        $favoriteRepo = new FavoriteRepository();
        $favorites = $favoriteRepo->paginateByUser(Auth::id(), 12);

        return View::render('favorites.index', ['favorites' => $favorites]);
    }

    public function store(int $bookId): void
    {
        if (!Auth::check()) {
            redirect('/login');
            return;
        }

        $favoriteRepo = new FavoriteRepository();

        if ($favoriteRepo->exists(Auth::id(), $bookId)) {
            Session::flash('error', 'Sách này đã có trong danh sách yêu thích của bạn.');
            back();
            return;
        }

        $favoriteRepo->add(Auth::id(), $bookId);
        Session::flash('success', 'Đã thêm sách vào danh sách yêu thích.');
        back();
    }

    public function destroy(int $bookId): void
    {
        if (!Auth::check()) {
            redirect('/login');
            return;
        }

        $favoriteRepo = new FavoriteRepository();
        $favoriteRepo->remove(Auth::id(), $bookId);

        Session::flash('success', 'Đã xoá sách khỏi danh sách yêu thích.');
        back();
    }
}
