<?php $title = 'Tài khoản' ?>
<?php ob_start() ?>

<?php if (session('status') === 'profile-updated'): ?>
    <div class="user-success-banner">Thông tin tài khoản đã được cập nhật.</div>
<?php endif ?>
<?php if (session('status') === 'password-updated'): ?>
    <div class="user-success-banner">Mật khẩu đã được cập nhật.</div>
<?php endif ?>

<!-- Profile Header -->
<div class="user-card" style="margin-bottom: 2rem; display: flex; align-items: center; gap: 1.25rem; flex-wrap: wrap;">
    <div class="profile-avatar" style="margin: 0;">
        <?= e(mb_strtoupper(mb_substr($user['name'] ?? 'U', 0, 1))) ?>
    </div>
    <div style="flex: 1; min-width: 0;">
        <h1 style="font-family: 'Figtree', sans-serif; font-size: 1.5rem; font-weight: 700; color: var(--brown); margin: 0;">
            <?= e($user['name']) ?>
        </h1>
        <p style="font-size: 0.875rem; color: var(--brown-light); margin: 0.25rem 0 0;">
            <?= e($user['email']) ?>
        </p>
    </div>
    <span class="profile-role-badge <?= ($user['role'] ?? '') === 'admin' ? 'admin' : 'user' ?>">
        <?= ($user['role'] ?? '') === 'admin' ? 'Quản trị viên' : 'Người dùng' ?>
    </span>
</div>

<div class="profile-layout">
    <!-- Left Sidebar -->
    <div class="profile-sidebar">
        <!-- Danger Zone -->
        <div class="danger-zone">
            <h3>Xoá tài khoản</h3>
            <p>Sau khi xoá tài khoản, tất cả dữ liệu của bạn sẽ bị mất vĩnh viễn. Hãy nhập mật khẩu để xác nhận.</p>
            <form method="POST" action="<?= route('profile.destroy') ?>">
                <?= csrf_field() ?>
                <?= method_field('DELETE') ?>
                <div class="user-form-group">
                    <input type="password" name="password" placeholder="Mật khẩu của bạn" required
                           class="user-input danger">
                    <?php if ($err = session('errors')['password'][0] ?? ''): ?>
                        <p class="user-form-error"><?= e($err) ?></p>
                    <?php endif ?>
                </div>
                <button type="submit" class="user-btn-danger">Xoá tài khoản</button>
            </form>
        </div>
    </div>

    <!-- Right Main Content -->
    <div style="display: flex; flex-direction: column; gap: 2rem;">
        <!-- Update Profile -->
        <div class="profile-section">
            <h2>Thông tin tài khoản</h2>
            <form method="POST" action="<?= route('profile.update') ?>">
                <?= csrf_field() ?>
                <?= method_field('PATCH') ?>

                <div class="user-form-group">
                    <label for="name" class="user-label">Họ tên</label>
                    <input id="name" type="text" name="name" value="<?= e($user['name']) ?>" required
                           class="user-input" placeholder="Nhập họ tên của bạn">
                    <?php if ($err = session('errors')['name'][0] ?? ''): ?>
                        <p class="user-form-error"><?= e($err) ?></p>
                    <?php endif ?>
                </div>

                <div class="user-form-group">
                    <label for="email" class="user-label">Email</label>
                    <input id="email" type="email" name="email" value="<?= e($user['email']) ?>" required
                           class="user-input" placeholder="Nhập địa chỉ email">
                    <?php if ($err = session('errors')['email'][0] ?? ''): ?>
                        <p class="user-form-error"><?= e($err) ?></p>
                    <?php endif ?>
                </div>

                <button type="submit" class="user-btn-primary">Lưu thay đổi</button>
            </form>
        </div>

        <!-- Update Password -->
        <div class="profile-section">
            <h2>Đổi mật khẩu</h2>
            <form method="POST" action="<?= route('password.update') ?>">
                <?= csrf_field() ?>
                <?= method_field('PUT') ?>

                <div class="user-form-group">
                    <label for="current_password" class="user-label">Mật khẩu hiện tại</label>
                    <input id="current_password" type="password" name="current_password" required
                           class="user-input" placeholder="Nhập mật khẩu hiện tại">
                    <?php if ($err = session('errors')['current_password'][0] ?? ''): ?>
                        <p class="user-form-error"><?= e($err) ?></p>
                    <?php endif ?>
                </div>

                <div class="user-form-group">
                    <label for="password" class="user-label">Mật khẩu mới</label>
                    <input id="password" type="password" name="password" required
                           class="user-input" placeholder="Nhập mật khẩu mới">
                    <?php if ($err = session('errors')['password'][0] ?? ''): ?>
                        <p class="user-form-error"><?= e($err) ?></p>
                    <?php endif ?>
                </div>

                <div class="user-form-group">
                    <label for="password_confirmation" class="user-label">Xác nhận mật khẩu mới</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required
                           class="user-input" placeholder="Nhập lại mật khẩu mới">
                </div>

                <button type="submit" class="user-btn-primary">Đổi mật khẩu</button>
            </form>
        </div>
    </div>
</div>

<?php $content = ob_get_clean() ?>
<?php require __DIR__ . '/../layouts/app.php' ?>
