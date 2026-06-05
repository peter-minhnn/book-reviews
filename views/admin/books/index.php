<?php $title = 'Quản lý sách' ?>
<?php $breadcrumbs = [['label' => 'Quản lý sách', 'url' => route('admin.books.index')]] ?>
<?php ob_start() ?>

<div class="admin-table-page">
    <div class="admin-page-header">
        <h1 class="admin-page-title">Quản lý sách</h1>
        <a href="<?= route('admin.books.create') ?>" class="admin-btn-primary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Thêm sách mới
        </a>
    </div>

    <div class="admin-table-card">
        <div class="admin-table-scroll">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tiêu đề</th>
                        <th>Tác giả</th>
                        <th>Danh mục</th>
                        <th style="text-align:center">Đánh giá</th>
                        <th>Ngày tạo</th>
                        <th style="text-align:right">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($books->total() === 0): ?>
                        <tr><td colspan="7" class="admin-table-empty">
                            <div style="font-size:2.5rem;margin-bottom:0.5rem">📚</div>
                            Chưa có sách nào.
                        </td></tr>
                    <?php else: ?>
                        <?php foreach ($books->items() as $book): ?>
                            <tr>
                                <td style="color:#6b7280"><?= $book['id'] ?></td>
                                <td style="font-weight:500;max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap"><?= e($book['title']) ?></td>
                                <td style="color:#4b5563"><?= e($book['author']) ?></td>
                                <td style="color:#4b5563"><?= e($book['category_name'] ?? '') ?></td>
                                <td style="text-align:center;color:#4b5563"><?= (int)($book['reviews_count'] ?? 0) ?></td>
                                <td style="color:#6b7280;white-space:nowrap"><?= e(date('d/m/Y', strtotime($book['created_at']))) ?></td>
                                <td style="text-align:right">
                                    <div class="admin-action-group">
                                        <a href="<?= route('admin.books.edit', ['book' => $book['id']]) ?>" class="admin-action-icon edit" data-tooltip="Sửa">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </a>
                                        <button onclick="window.dispatchEvent(new CustomEvent('open-confirm', { detail: { action: '<?= route('admin.books.destroy', ['book' => $book['id']]) ?>', method: 'DELETE', message: 'Xoá sách \"<?= e(addslashes($book['title'])) ?>\"?' } }))"
                                                class="admin-action-icon delete" data-tooltip="Xoá">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    <?php endif ?>
                </tbody>
            </table>
        </div>
        <?php if ($books->hasPages()): ?>
            <div class="admin-table-pagination">
                <?= view('vendor.pagination.tailwind', ['paginator' => $books]) ?>
            </div>
        <?php endif ?>
    </div>
</div>

<?php $content = ob_get_clean() ?>
<?php require __DIR__ . '/../../layouts/admin.php' ?>
