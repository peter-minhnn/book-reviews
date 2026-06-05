<?php $title = 'Sửa người dùng' ?>
<?php $breadcrumbs = [
    ['label' => 'Quản lý người dùng', 'url' => route('admin.users.index')],
    ['label' => 'Sửa người dùng', 'url' => '#'],
] ?>
<?php ob_start() ?>

<div class="admin-form-page">
    <div class="admin-form-shell narrow">
        <h1 class="admin-page-title" style="margin-bottom:1.5rem">Sửa người dùng: <?= e($user['name']) ?></h1>

    <form method="POST" action="<?= route('admin.users.update', ['user' => $user['id']]) ?>"
          class="admin-form-card" style="display:flex;flex-direction:column;gap:1rem">
        <?= csrf_field() ?>
        <?= method_field('PUT') ?>

        <div>
            <label class="admin-form-label">Tên <span class="required">*</span></label>
            <input type="text" name="name" value="<?= e($user['name']) ?>" required
                   placeholder="Nhập họ tên" class="admin-input">
            <?php if ($err = session('errors')['name'][0] ?? ''): ?><p class="admin-form-error"><?= e($err) ?></p><?php endif ?>
        </div>

        <div>
            <label class="admin-form-label">Email <span class="required">*</span></label>
            <input type="email" name="email" value="<?= e($user['email']) ?>" required
                   placeholder="Nhập địa chỉ email" class="admin-input">
            <?php if ($err = session('errors')['email'][0] ?? ''): ?><p class="admin-form-error"><?= e($err) ?></p><?php endif ?>
        </div>

        <div>
            <label class="admin-form-label">Vai trò <span class="required">*</span></label>
            <div class="admin-select-wrap">
                <select name="role" required>
                    <option value="">-- Chọn vai trò --</option>
                    <option value="user" <?= $user['role'] === 'user' ? 'selected' : '' ?>>User</option>
                    <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                </select>
            </div>
            <?php if ($user['id'] === (auth()['id'] ?? 0)): ?>
                <p style="font-size:0.75rem;color:#d97706;margin-top:0.25rem">Bạn không thể tự hạ quyền admin của chính mình.</p>
            <?php endif ?>
        </div>

        <div style="border-top:1px solid #f3f4f6;padding-top:1rem">
            <p style="font-size:0.875rem;font-weight:600;color:#374151;margin-bottom:0.75rem">
                Đổi mật khẩu <span style="font-size:0.75rem;color:#9ca3af;font-weight:400">(để trống nếu không đổi)</span>
            </p>

            <div style="margin-bottom:1rem">
                <label class="admin-form-label">Mật khẩu mới</label>
                <input type="password" name="new_password" minlength="8"
                       placeholder="Nhập mật khẩu mới (tối thiểu 8 ký tự)" class="admin-input">
                <?php if ($err = session('errors')['new_password'][0] ?? ''): ?><p class="admin-form-error"><?= e($err) ?></p><?php endif ?>
            </div>

            <div>
                <label class="admin-form-label">Xác nhận mật khẩu mới</label>
                <input type="password" name="new_password_confirmation" minlength="8"
                       placeholder="Nhập lại mật khẩu mới" class="admin-input">
            </div>
        </div>

        <div class="admin-form-actions">
            <button type="submit" class="admin-btn-primary">Cập nhật</button>
            <a href="<?= route('admin.users.index') ?>" class="admin-btn-secondary">Huỷ</a>
        </div>
        </form>
    </div>
</div>

<?php $content = ob_get_clean() ?>
<?php require __DIR__ . '/../../layouts/admin.php' ?>
