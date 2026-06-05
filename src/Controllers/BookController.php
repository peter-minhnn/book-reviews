<?php

namespace App\Controllers;

use App\Core\App;
use App\Core\Auth;
use App\Core\Request;
use App\Core\View;
use App\Repositories\BookRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\FavoriteRepository;
use App\Repositories\ReviewRepository;

class BookController
{
    public function index(): string
    {
        $isAjax = Request::isAjax();

        if (!$isAjax) {
            $categoryRepo = new CategoryRepository();
            $categories = $categoryRepo->all();
            return View::render('books.index', ['categories' => $categories]);
        }

        $bookRepo = new BookRepository();
        $builder = $bookRepo->query();

        if (Request::filled('search')) {
            $builder->search(Request::input('search'));
        }

        if (Request::filled('category')) {
            $builder->whereCategory((int) Request::input('category'));
        }

        if (Request::input('sort') === 'rating') {
            $builder->orderByRating();
        } else {
            $builder->orderByLatest();
        }

        $books = $bookRepo->paginateWithQuery($builder, 12);

        $categoryRepo = new CategoryRepository();
        $categories = $categoryRepo->all();

        return View::render('books.grid', [
            'books' => $books,
            'categories' => $categories,
        ]);
    }

    public function show(int $id): string
    {
        $bookRepo = new BookRepository();
        $book = $bookRepo->findById($id);

        if (!$book) {
            http_response_code(404);
            return View::render('errors.404');
        }

        $reviewRepo = new ReviewRepository();
        $reviews = $reviewRepo->findByBook($id);
        $averageRating = $bookRepo->averageRating($id);
        $reviewsCount = $bookRepo->reviewsCount($id);

        $editingReview = null;
        $isFavorite = false;
        if (Auth::check()) {
            $isFavorite = (new FavoriteRepository())->exists(Auth::id(), $id);

            $editReviewId = (int) ($_GET['edit_review'] ?? 0);
            if ($editReviewId > 0) {
                $candidate = $reviewRepo->findById($editReviewId);
                if (
                    $candidate
                    && (int) $candidate['book_id'] === $id
                    && (int) $candidate['user_id'] === Auth::id()
                ) {
                    $editingReview = $candidate;
                }
            }
        }

        return View::render('books.show', compact(
            'book', 'reviews', 'averageRating', 'reviewsCount', 'editingReview', 'isFavorite'
        ));
    }

    public function byCategory(int $categoryId): string
    {
        $_GET['category'] = $categoryId;

        $isAjax = Request::isAjax();

        if (!$isAjax) {
            $categoryRepo = new CategoryRepository();
            $categories = $categoryRepo->all();
            return View::render('books.index', ['categories' => $categories]);
        }

        $bookRepo = new BookRepository();
        $builder = $bookRepo->query();
        $builder->whereCategory($categoryId);

        $books = $bookRepo->paginateWithQuery($builder, 12);

        $categoryRepo = new CategoryRepository();
        $categories = $categoryRepo->all();

        return View::render('books.grid', [
            'books' => $books,
            'categories' => $categories,
        ]);
    }

    public function search(): string
    {
        $q = Request::input('q', '');
        if (mb_strlen($q) < 2 || mb_strlen($q) > 100) {
            $q = '';
        }

        $isAjax = Request::isAjax();

        if (!$isAjax) {
            $categoryRepo = new CategoryRepository();
            $categories = $categoryRepo->all();
            return View::render('books.index', ['categories' => $categories]);
        }

        $bookRepo = new BookRepository();
        $builder = $bookRepo->query();
        if ($q) {
            $builder->search($q);
        }
        $builder->orderByLatest();

        $books = $bookRepo->paginateWithQuery($builder, 12);

        $categoryRepo = new CategoryRepository();
        $categories = $categoryRepo->all();

        return View::render('books.grid', [
            'books' => $books,
            'categories' => $categories,
        ]);
    }
}
