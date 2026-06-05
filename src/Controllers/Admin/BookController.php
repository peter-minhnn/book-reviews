<?php

namespace App\Controllers\Admin;

use App\Core\Session;
use App\Core\View;
use App\Repositories\BookRepository;
use App\Repositories\CategoryRepository;

class BookController
{
    public function index(): string
    {
        $bookRepo = new BookRepository();
        $builder = $bookRepo->query()->orderByLatest();
        $books = $bookRepo->paginateWithQuery($builder, 10);

        return View::render('admin.books.index', ['books' => $books]);
    }

    public function create(): string
    {
        $categoryRepo = new CategoryRepository();
        $categories = $categoryRepo->all();
        return View::render('admin.books.create', ['categories' => $categories]);
    }

    public function store(): void
    {
        $data = $this->validateBook();
        if ($data === false) {
            redirect('/admin/books/create');
            return;
        }

        // Handle cover image upload
        if ($this->hasFile('cover_image')) {
            $data['cover_image'] = $this->uploadCover('cover_image');
        }

        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');

        $bookRepo = new BookRepository();
        $bookRepo->create($data);

        Session::flash('success', 'Đã tạo sách thành công.');
        redirect('/admin/books');
    }

    public function edit(int $id): string
    {
        $bookRepo = new BookRepository();
        $book = $bookRepo->findById($id);

        if (!$book) {
            http_response_code(404);
            return '';
        }

        $categoryRepo = new CategoryRepository();
        $categories = $categoryRepo->all();

        return View::render('admin.books.edit', ['book' => $book, 'categories' => $categories]);
    }

    public function update(int $id): void
    {
        $bookRepo = new BookRepository();
        $book = $bookRepo->findById($id);

        if (!$book) {
            http_response_code(404);
            return;
        }

        $data = $this->validateBook($id);
        if ($data === false) {
            redirect('/admin/books/' . $id . '/edit');
            return;
        }

        // Handle cover image upload
        if ($this->hasFile('cover_image')) {
            // Delete old cover
            if ($book['cover_image']) {
                $oldPath = __DIR__ . '/../../../public/uploads/' . $book['cover_image'];
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }
            $data['cover_image'] = $this->uploadCover('cover_image');
        }

        $data['updated_at'] = date('Y-m-d H:i:s');
        $bookRepo->update($id, $data);

        Session::flash('success', 'Đã cập nhật sách thành công.');
        redirect('/admin/books');
    }

    public function destroy(int $id): void
    {
        $bookRepo = new BookRepository();
        $bookRepo->delete($id);

        Session::flash('success', 'Đã xoá sách thành công.');
        redirect('/admin/books');
    }

    private function validateBook(?int $bookId = null): array|false
    {
        $errors = [];
        $title = trim($_POST['title'] ?? '');
        $author = trim($_POST['author'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $publishedYear = $_POST['published_year'] ?? '';
        $categoryId = $_POST['category_id'] ?? '';

        if ($title === '' || mb_strlen($title) > 255) {
            $errors['title'] = ['Tiêu đề sách không được để trống và không quá 255 ký tự.'];
        }
        if ($author === '' || mb_strlen($author) > 255) {
            $errors['author'] = ['Tác giả không được để trống và không quá 255 ký tự.'];
        }
        if ($description !== '' && mb_strlen($description) > 5000) {
            $errors['description'] = ['Mô tả không được vượt quá 5000 ký tự.'];
        }
        if ($publishedYear !== '' && (!is_numeric($publishedYear) || (int) $publishedYear < 1000 || (int) $publishedYear > (int) date('Y'))) {
            $errors['published_year'] = ['Năm xuất bản không hợp lệ.'];
        }
        if ($categoryId === '') {
            $errors['category_id'] = ['Vui lòng chọn danh mục.'];
        }

        // Validate cover image
        if ($this->hasFile('cover_image')) {
            $file = $_FILES['cover_image'];
            $allowedMimes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
            if (!in_array($file['type'], $allowedMimes)) {
                $errors['cover_image'] = ['Ảnh bìa phải là file jpeg, jpg, png hoặc webp.'];
            }
            if ($file['size'] > 2 * 1024 * 1024) {
                $errors['cover_image'] = ['Ảnh bìa không được vượt quá 2MB.'];
            }
        }

        if (!empty($errors)) {
            Session::flash('errors', $errors);
            Session::flash('_old', $_POST);
            return false;
        }

        return [
            'title' => $title,
            'author' => $author,
            'description' => $description ?: null,
            'published_year' => $publishedYear !== '' ? (int) $publishedYear : null,
            'category_id' => (int) $categoryId,
        ];
    }

    private function hasFile(string $key): bool
    {
        return isset($_FILES[$key]) && $_FILES[$key]['error'] === UPLOAD_ERR_OK;
    }

    private function uploadCover(string $key): string
    {
        $file = $_FILES[$key];
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid('cover_') . '.' . $ext;
        $uploadDir = __DIR__ . '/../../../public/uploads/covers/';

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        move_uploaded_file($file['tmp_name'], $uploadDir . $filename);

        return 'covers/' . $filename;
    }
}
