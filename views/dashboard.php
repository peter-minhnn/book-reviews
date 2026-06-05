<?php $title = 'Bảng điều khiển' ?>
<?php ob_start() ?>

<div class="dashboard-hero">
    <div class="icon">👋</div>
    <h1>Bạn đã đăng nhập!</h1>
    <p>Chào mừng, <?= e(auth()['name'] ?? '') ?>!</p>
    <div class="dashboard-actions">
        <a href="<?= route('books.index') ?>" class="user-btn-primary">Duyệt sách</a>
        <a href="<?= route('profile.edit') ?>" class="user-btn-secondary">Quản lý tài khoản</a>
    </div>
</div>

<?php $content = ob_get_clean() ?>
<?php require __DIR__ . '/layouts/app.php' ?>
