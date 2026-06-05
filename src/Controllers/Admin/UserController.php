<?php

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Session;
use App\Core\View;
use App\Repositories\UserRepository;

class UserController
{
    public function index(): string
    {
        $userRepo = new UserRepository();
        $users = $userRepo->paginate(10);

        return View::render('admin.users.index', ['users' => $users]);
    }

    public function edit(int $id): string
    {
        $userRepo = new UserRepository();
        $user = $userRepo->findById($id);

        if (!$user) {
            http_response_code(404);
            return '';
        }

        return View::render('admin.users.edit', ['user' => $user]);
    }

    public function update(int $id): void
    {
        $userRepo = new UserRepository();
        $user = $userRepo->findById($id);

        if (!$user) {
            http_response_code(404);
            return;
        }

        $name = trim($_POST['name'] ?? '');
        $email = trim(strtolower($_POST['email'] ?? ''));
        $role = $_POST['role'] ?? 'user';
        $newPassword = $_POST['new_password'] ?? '';
        $newPasswordConfirmation = $_POST['new_password_confirmation'] ?? '';

        $errors = [];
        if ($name === '' || mb_strlen($name) > 255) {
            $errors['name'] = ['Tên không được để trống và không quá 255 ký tự.'];
        }
        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = ['Email không hợp lệ.'];
        }
        if (!in_array($role, ['admin', 'user'])) {
            $errors['role'] = ['Vai trò không hợp lệ.'];
        }

        // Check unique email
        $existing = $userRepo->findByEmail($email);
        if ($existing && $existing['id'] !== $id) {
            $errors['email'] = ['Email này đã được sử dụng.'];
        }

        // Prevent self-demotion
        if ($id === Auth::id() && $role !== 'admin') {
            Session::flash('error', 'Bạn không thể xoá quyền admin của chính mình.');
            redirect('/admin/users/' . $id . '/edit');
            return;
        }

        if ($newPassword !== '') {
            if (mb_strlen($newPassword) < 8) {
                $errors['new_password'] = ['Mật khẩu mới phải có ít nhất 8 ký tự.'];
            }
            if ($newPassword !== $newPasswordConfirmation) {
                $errors['new_password'] = ['Xác nhận mật khẩu không khớp.'];
            }
        }

        if (!empty($errors)) {
            Session::flash('errors', $errors);
            redirect('/admin/users/' . $id . '/edit');
            return;
        }

        $updateData = [
            'name' => $name,
            'email' => $email,
            'role' => $role,
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        if ($newPassword !== '') {
            $updateData['password'] = password_hash($newPassword, PASSWORD_DEFAULT);
        }

        $userRepo->update($id, $updateData);

        Session::flash('success', 'Đã cập nhật người dùng thành công.');
        redirect('/admin/users');
    }

    public function destroy(int $id): void
    {
        if ($id === Auth::id()) {
            Session::flash('error', 'Bạn không thể xoá tài khoản của chính mình.');
            redirect('/admin/users');
            return;
        }

        $userRepo = new UserRepository();
        $userRepo->delete($id);

        Session::flash('success', 'Đã xoá người dùng thành công.');
        redirect('/admin/users');
    }
}
