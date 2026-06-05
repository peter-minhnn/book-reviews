<?php

use App\Controllers\HomeController;
use App\Controllers\BookController;
use App\Controllers\ReviewController;
use App\Controllers\FavoriteController;
use App\Controllers\ProfileController;
use App\Controllers\ReviewStreamController;
use App\Controllers\Auth\RegisteredUserController;
use App\Controllers\Auth\AuthenticatedSessionController;
use App\Controllers\Auth\PasswordController;
use App\Controllers\Admin\DashboardController;
use App\Controllers\Admin\BookController as AdminBookController;
use App\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Controllers\Admin\ReviewController as AdminReviewController;
use App\Controllers\Admin\UserController as AdminUserController;
use App\Middleware\AuthMiddleware;
use App\Middleware\AdminMiddleware;
use App\Middleware\GuestMiddleware;

$router = $app->router();

// Home
$router->get('/', [HomeController::class, 'index'])->name('home');

// Books
$router->get('/books', [BookController::class, 'index'])->name('books.index');
$router->get('/books/search', [BookController::class, 'search'])->name('books.search');
$router->get('/books/category/{category}', [BookController::class, 'byCategory'])->name('books.category');
$router->get('/books/{book}', [BookController::class, 'show'])->name('books.show');

// SSE
$router->get('/reviews/stream', [ReviewStreamController::class, 'stream'])->name('reviews.stream');
$router->get('/events/latest-reviews', [ReviewStreamController::class, 'stream'])->name('events.reviews');

// Auth routes (guest)
$router->get('/register', [RegisteredUserController::class, 'create'])->name('register');
$router->post('/register', [RegisteredUserController::class, 'store']);
$router->get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
$router->post('/login', [AuthenticatedSessionController::class, 'store']);
$router->post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

// Reviews (auth)
$router->group(['middleware' => [AuthMiddleware::class]], function () use ($router) {
    $router->post('/books/{book}/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    $router->put('/reviews/{review}', [ReviewController::class, 'update'])->name('reviews.update');
    $router->delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
});

// Favorites (auth)
$router->group(['middleware' => [AuthMiddleware::class]], function () use ($router) {
    $router->get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');
    $router->post('/favorites/{book}', [FavoriteController::class, 'store'])->name('favorites.store');
    $router->delete('/favorites/{book}', [FavoriteController::class, 'destroy'])->name('favorites.destroy');
});

// Dashboard (auth required)
$router->get('/dashboard', function () {
    return \App\Core\View::render('dashboard');
})->middleware([AuthMiddleware::class])->name('dashboard');

// Profile (auth)
$router->group(['middleware' => [AuthMiddleware::class]], function () use ($router) {
    $router->get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    $router->patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    $router->delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    $router->put('/password', [PasswordController::class, 'update'])->name('password.update');
});

// Admin (auth + admin)
$router->group(['middleware' => [AuthMiddleware::class, AdminMiddleware::class], 'prefix' => '/admin', 'name' => 'admin.'], function () use ($router) {
    $router->get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Books
    $router->get('/books', [AdminBookController::class, 'index'])->name('books.index');
    $router->get('/books/create', [AdminBookController::class, 'create'])->name('books.create');
    $router->post('/books', [AdminBookController::class, 'store'])->name('books.store');
    $router->get('/books/{book}/edit', [AdminBookController::class, 'edit'])->name('books.edit');
    $router->put('/books/{book}', [AdminBookController::class, 'update'])->name('books.update');
    $router->patch('/books/{book}', [AdminBookController::class, 'update']);
    $router->delete('/books/{book}', [AdminBookController::class, 'destroy'])->name('books.destroy');

    // Categories
    $router->get('/categories', [AdminCategoryController::class, 'index'])->name('categories.index');
    $router->get('/categories/create', [AdminCategoryController::class, 'create'])->name('categories.create');
    $router->post('/categories', [AdminCategoryController::class, 'store'])->name('categories.store');
    $router->get('/categories/{category}/edit', [AdminCategoryController::class, 'edit'])->name('categories.edit');
    $router->put('/categories/{category}', [AdminCategoryController::class, 'update'])->name('categories.update');
    $router->patch('/categories/{category}', [AdminCategoryController::class, 'update']);
    $router->delete('/categories/{category}', [AdminCategoryController::class, 'destroy'])->name('categories.destroy');

    // Reviews
    $router->get('/reviews', [AdminReviewController::class, 'index'])->name('reviews.index');
    $router->delete('/reviews/{review}', [AdminReviewController::class, 'destroy'])->name('reviews.destroy');

    // Users
    $router->get('/users', [AdminUserController::class, 'index'])->name('users.index');
    $router->get('/users/{user}/edit', [AdminUserController::class, 'edit'])->name('users.edit');
    $router->put('/users/{user}', [AdminUserController::class, 'update'])->name('users.update');
    $router->patch('/users/{user}', [AdminUserController::class, 'update']);
    $router->delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');
});
