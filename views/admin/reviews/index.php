<?php $title = 'Quản lý đánh giá' ?>
<?php $breadcrumbs = [['label' => 'Quản lý đánh giá', 'url' => route('admin.reviews.index')]] ?>
<?php ob_start() ?>

<div class="admin-table-page">
    <h1 class="admin-page-title" style="margin-bottom:1rem">Quản lý đánh giá</h1>

    <div class="admin-table-card">
        <div class="admin-table-scroll">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Người dùng</th>
                        <th>Sách</th>
                        <th style="text-align:center">Xếp hạng</th>
                        <th>Nội dung</th>
                        <th>Ngày tạo</th>
                        <th style="text-align:right">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($reviews->total() === 0): ?>
                        <tr><td colspan="7" class="admin-table-empty">
                            <div style="font-size:2.5rem;margin-bottom:0.5rem">⭐</div>
                            Chưa có đánh giá nào.
                        </td></tr>
                    <?php else: ?>
                        <?php foreach ($reviews->items() as $review): ?>
                            <tr>
                                <td style="color:#6b7280"><?= $review['id'] ?></td>
                                <td style="font-weight:500;white-space:nowrap"><?= e($review['user_name'] ?? 'Unknown') ?></td>
                                <td style="color:#4b5563;max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap"><?= e($review['book_title'] ?? '') ?></td>
                                <td style="text-align:center">
                                    <?php for ($s = 1; $s <= 5; $s++): ?>
                                        <span style="color:<?= $s <= $review['rating'] ? '#f59e0b' : '#d1d5db' ?>;font-size:0.875rem">★</span>
                                    <?php endfor ?>
                                </td>
                                <td style="color:#6b7280;max-width:250px">
                                    <span style="display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden"><?= e(mb_substr($review['content'] ?? '', 0, 100)) ?></span>
                                </td>
                                <td style="color:#6b7280;white-space:nowrap"><?= e(date('d/m/Y', strtotime($review['created_at']))) ?></td>
                                <td style="text-align:right">
                                    <div class="admin-action-group">
                                        <button onclick="window.dispatchEvent(new CustomEvent('open-confirm', { detail: { action: '<?= route('admin.reviews.destroy', ['review' => $review['id']]) ?>', method: 'DELETE', message: 'Xoá đánh giá này?' } }))"
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
        <?php if ($reviews->hasPages()): ?>
            <div class="admin-table-pagination">
                <?= view('vendor.pagination.tailwind', ['paginator' => $reviews]) ?>
            </div>
        <?php endif ?>
    </div>
</div>

<?php $content = ob_get_clean() ?>
<?php require __DIR__ . '/../../layouts/admin.php' ?>
