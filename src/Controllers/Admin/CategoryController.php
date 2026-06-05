<?php

namespace App\Controllers\Admin;

use App\Core\Session;
use App\Core\View;
use App\Repositories\CategoryRepository;

class CategoryController
{
    public function index(): string
    {
        $categoryRepo = new CategoryRepository();
        $categories = $categoryRepo->paginate(10);

        return View::render('admin.categories.index', ['categories' => $categories]);
    }

    public function create(): string
    {
        return View::render('admin.categories.create');
    }

    public function store(): void
    {
        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');

        $errors = [];
        if ($name === '' || mb_strlen($name) > 100) {
            $errors['name'] = ['Tên danh mục không được để trống và không quá 100 ký tự.'];
        }
        if ($description !== '' && mb_strlen($description) > 1000) {
            $errors['description'] = ['Mô tả không được vượt quá 1000 ký tự.'];
        }

        // Check unique name
        $categoryRepo = new CategoryRepository();
        $all = $categoryRepo->all();
        foreach ($all as $cat) {
            if (mb_strtolower($cat['name']) === mb_strtolower($name)) {
                $errors['name'] = ['Tên danh mục này đã tồn tại.'];
                break;
            }
        }

        if (!empty($errors)) {
            Session::flash('errors', $errors);
            Session::flash('_old', $_POST);
            redirect('/admin/categories/create');
            return;
        }

        $categoryRepo->create([
            'name' => $name,
            'slug' => $this->slugify($name),
            'description' => $description ?: null,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        Session::flash('success', 'Đã tạo danh mục thành công.');
        redirect('/admin/categories');
    }

    public function edit(int $id): string
    {
        $categoryRepo = new CategoryRepository();
        $category = $categoryRepo->findById($id);

        if (!$category) {
            http_response_code(404);
            return '';
        }

        return View::render('admin.categories.edit', ['category' => $category]);
    }

    public function update(int $id): void
    {
        $categoryRepo = new CategoryRepository();
        $category = $categoryRepo->findById($id);

        if (!$category) {
            http_response_code(404);
            return;
        }

        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');

        $errors = [];
        if ($name === '' || mb_strlen($name) > 100) {
            $errors['name'] = ['Tên danh mục không được để trống và không quá 100 ký tự.'];
        }
        if ($description !== '' && mb_strlen($description) > 1000) {
            $errors['description'] = ['Mô tả không được vượt quá 1000 ký tự.'];
        }

        // Check unique name excluding current
        $all = $categoryRepo->all();
        foreach ($all as $cat) {
            if ($cat['id'] !== $id && mb_strtolower($cat['name']) === mb_strtolower($name)) {
                $errors['name'] = ['Tên danh mục này đã tồn tại.'];
                break;
            }
        }

        if (!empty($errors)) {
            Session::flash('errors', $errors);
            redirect('/admin/categories/' . $id . '/edit');
            return;
        }

        $categoryRepo->update($id, [
            'name' => $name,
            'slug' => $this->slugify($name),
            'description' => $description ?: null,
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        Session::flash('success', 'Đã cập nhật danh mục thành công.');
        redirect('/admin/categories');
    }

    public function destroy(int $id): void
    {
        $categoryRepo = new CategoryRepository();
        $categoryRepo->delete($id);

        Session::flash('success', 'Đã xoá danh mục thành công.');
        redirect('/admin/categories');
    }

    private function slugify(string $text): string
    {
        // Convert Vietnamese characters to ASCII
        $text = preg_replace('/[^\x00-\x7F]+/', '', $text);
        $text = strtolower(trim($text));
        $text = preg_replace('/[^a-z0-9]+/', '-', $text);
        $text = trim($text, '-');
        return $text ?: uniqid();
    }
}
