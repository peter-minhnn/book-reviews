<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Session;
use App\Repositories\ReviewRepository;

class ReviewController
{
    public function store(int $bookId): void
    {
        if (!Auth::check()) {
            redirect('/login');
            return;
        }

        $rating = (int) ($_POST['rating'] ?? 0);
        $content = trim($_POST['content'] ?? '');

        $errors = [];
        if ($rating < 1 || $rating > 5) {
            $errors['rating'] = ['Vui lòng chọn xếp hạng sao từ 1 đến 5.'];
        }
        if ($content !== '' && mb_strlen($content) > 2000) {
            $errors['content'] = ['Nội dung đánh giá không được vượt quá 2000 ký tự.'];
        }

        if (!empty($errors)) {
            Session::flash('errors', $errors);
            Session::flash('_old', $_POST);
            redirect('/books/' . $bookId);
            return;
        }

        (new ReviewRepository())->create([
            'user_id' => Auth::id(),
            'book_id' => $bookId,
            'rating' => $rating,
            'content' => $content ?: null,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        Session::flash('success', 'Đánh giá của bạn đã được đăng thành công.');
        redirect('/books/' . $bookId);
    }

    public function update(int $reviewId): void
    {
        if (!Auth::check()) {
            redirect('/login');
            return;
        }

        $reviewRepo = new ReviewRepository();
        $review = $reviewRepo->findById($reviewId);

        if (!$review) {
            http_response_code(404);
            return;
        }

        if ((int) $review['user_id'] !== Auth::id()) {
            http_response_code(403);
            echo 'Bạn chỉ có thể sửa đánh giá của chính mình.';
            return;
        }

        $rating = (int) ($_POST['rating'] ?? 0);
        $content = trim($_POST['content'] ?? '');

        $errors = [];
        if ($rating < 1 || $rating > 5) {
            $errors['rating'] = ['Vui lòng chọn xếp hạng sao từ 1 đến 5.'];
        }
        if ($content !== '' && mb_strlen($content) > 2000) {
            $errors['content'] = ['Nội dung đánh giá không được vượt quá 2000 ký tự.'];
        }

        if (!empty($errors)) {
            Session::flash('errors', $errors);
            Session::flash('_old', $_POST);
            redirect('/books/' . $review['book_id'] . '?edit_review=' . $reviewId . '#review-form');
            return;
        }

        $reviewRepo->update($reviewId, [
            'rating' => $rating,
            'content' => $content ?: null,
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        Session::flash('success', 'Đánh giá của bạn đã được cập nhật thành công.');
        redirect('/books/' . $review['book_id']);
    }

    public function destroy(int $reviewId): void
    {
        if (!Auth::check()) {
            redirect('/login');
            return;
        }

        $reviewRepo = new ReviewRepository();
        $review = $reviewRepo->findById($reviewId);

        if (!$review) {
            http_response_code(404);
            return;
        }

        // Only review owner or admin can delete
        if ((int) $review['user_id'] !== Auth::id() && !Auth::isAdmin()) {
            http_response_code(403);
            echo 'Bạn chỉ có thể xoá đánh giá của chính mình.';
            return;
        }

        $bookId = $review['book_id'];
        $reviewRepo->delete($reviewId);

        Session::flash('success', 'Đánh giá của bạn đã được xoá.');
        redirect('/books/' . $bookId);
    }
}
