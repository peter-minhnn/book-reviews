<?php

namespace App\Controllers\Admin;

use App\Core\Session;
use App\Core\View;
use App\Repositories\ReviewRepository;

class ReviewController
{
    public function index(): string
    {
        $reviewRepo = new ReviewRepository();
        $reviews = $reviewRepo->paginate(15);

        return View::render('admin.reviews.index', ['reviews' => $reviews]);
    }

    public function destroy(int $id): void
    {
        $reviewRepo = new ReviewRepository();
        $reviewRepo->delete($id);

        Session::flash('success', 'Đã xoá đánh giá thành công.');
        redirect('/admin/reviews');
    }
}
