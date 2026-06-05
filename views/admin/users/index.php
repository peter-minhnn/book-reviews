<?php $title = 'Quản lý người dùng' ?>
<?php $breadcrumbs = [['label' => 'Quản lý người dùng', 'url' => route('admin.users.index')]] ?>
<?php ob_start() ?>

<div class="admin-table-page">
    <h1 class="admin-page-title" style="margin-bottom:1rem">Quản lý người dùng</h1>

    <div class="admin-table-card">
        <div class="admin-table-scroll">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tên</th>
                        <th>Email</th>
                        <th style="text-align:center">Vai trò</th>
                        <th style="text-align:center">Đánh giá</th>
                        <th>Ngày tạo</th>
                        <th style="text-align:right">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($users->total() === 0): ?>
                        <tr><td colspan="7" class="admin-table-empty">
                            <div style="font-size:2.5rem;margin-bottom:0.5rem">👥</div>
                            Chưa có người dùng nào.
                        </td></tr>
                    <?php else: ?>
                        <?php foreach ($users->items() as $u): ?>
                            <tr>
                                <td style="color:#6b7280"><?= $u['id'] ?></td>
                                <td style="font-weight:500"><?= e($u['name']) ?></td>
                                <td style="color:#4b5563"><?= e($u['email']) ?></td>
                                <td style="text-align:center">
                                    <span class="admin-badge <?= $u['role'] === 'admin' ? 'admin-badge-admin' : 'admin-badge-user' ?>">
                                        <?= $u['role'] === 'admin' ? 'Admin' : 'User' ?>
                                    </span>
                                </td>
                                <td style="text-align:center;color:#4b5563"><?= (int)($u['reviews_count'] ?? 0) ?></td>
                                <td style="color:#6b7280;white-space:nowrap"><?= e(date('d/m/Y', strtotime($u['created_at']))) ?></td>
                                <td style="text-align:right">
                                    <div class="admin-action-group">
                                        <a href="<?= route('admin.users.edit', ['user' => $u['id']]) ?>" class="admin-action-icon edit" data-tooltip="Sửa">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </a>
                                        <?php if ($u['id'] !== (auth()['id'] ?? 0)): ?>
                                            <button onclick="window.dispatchEvent(new CustomEvent('open-confirm', { detail: { action: '<?= route('admin.users.destroy', ['user' => $u['id']]) ?>', method: 'DELETE', message: 'Xoá người dùng \"<?= e(addslashes($u['name'])) ?>\"?' } }))"
                                                    class="admin-action-icon delete" data-tooltip="Xoá">
                                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        <?php endif ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    <?php endif ?>
                </tbody>
            </table>
        </div>
        <?php if ($users->hasPages()): ?>
            <div class="admin-table-pagination">
                <?= view('vendor.pagination.tailwind', ['paginator' => $users]) ?>
            </div>
        <?php endif ?>
    </div>
</div>

<?php $content = ob_get_clean() ?>
<?php require __DIR__ . '/../../layouts/admin.php' ?>
