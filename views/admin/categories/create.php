<?php $title = 'Thêm danh mục mới' ?>
<?php $breadcrumbs = [
    ['label' => 'Quản lý danh mục', 'url' => route('admin.categories.index')],
    ['label' => 'Thêm danh mục mới', 'url' => '#'],
] ?>
<?php ob_start() ?>

<div class="admin-form-page">
    <div class="admin-form-shell narrow">
        <h1 class="admin-page-title" style="margin-bottom:1.5rem">Thêm danh mục mới</h1>

    <form method="POST" action="<?= route('admin.categories.store') ?>" class="admin-form-card" style="display:flex;flex-direction:column;gap:1rem">
        <?= csrf_field() ?>

        <div>
            <label class="admin-form-label">Tên danh mục <span class="required">*</span></label>
            <input type="text" name="name" value="<?= e(old('name')) ?>" required maxlength="100"
                   placeholder="Nhập tên danh mục" class="admin-input">
            <?php if ($err = session('errors')['name'][0] ?? ''): ?><p class="admin-form-error"><?= e($err) ?></p><?php endif ?>
        </div>

        <div>
            <label class="admin-form-label">Mô tả</label>
            <textarea name="description" rows="3" maxlength="1000"
                      placeholder="Nhập mô tả về danh mục này..." class="admin-textarea"><?= e(old('description')) ?></textarea>
            <p class="admin-form-help">Tối đa 1000 ký tự.</p>
        </div>

        <div class="admin-form-actions">
            <button type="submit" class="admin-btn-primary">Tạo danh mục</button>
            <a href="<?= route('admin.categories.index') ?>" class="admin-btn-secondary">Huỷ</a>
        </div>
        </form>
    </div>
</div>

<?php $content = ob_get_clean() ?>
<?php require __DIR__ . '/../../layouts/admin.php' ?>
