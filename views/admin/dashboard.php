<?php $title = 'Bảng điều khiển' ?>
<?php ob_start() ?>

<div class="admin-dashboard-page">
    <h1 class="admin-page-title" style="margin-bottom:1.5rem">Bảng điều khiển</h1>

    <!-- Stats Cards -->
    <div class="admin-stats-grid">
        <div class="admin-stat-card">
            <div class="admin-stat-icon" style="background:#eff6ff">📚</div>
            <div>
                <p class="admin-stat-label">Tổng số sách</p>
                <p class="admin-stat-value"><?= number_format($totalBooks) ?></p>
            </div>
        </div>
        <div class="admin-stat-card">
            <div class="admin-stat-icon" style="background:#ecfdf5">👥</div>
            <div>
                <p class="admin-stat-label">Tổng người dùng</p>
                <p class="admin-stat-value"><?= number_format($totalUsers) ?></p>
            </div>
        </div>
        <div class="admin-stat-card">
            <div class="admin-stat-icon" style="background:#fffbeb">⭐</div>
            <div>
                <p class="admin-stat-label">Tổng đánh giá</p>
                <p class="admin-stat-value"><?= number_format($totalReviews) ?></p>
            </div>
        </div>
    </div>

    <!-- Dashboard Grid: Top Rated + Latest Reviews side by side -->
    <div class="admin-dashboard-grid">
        <!-- Top Rated Books -->
        <div class="admin-form-card">
            <h2 style="font-size:1.125rem;font-weight:600;color:#111827;margin-bottom:1rem">⭐ Sách được đánh giá cao nhất</h2>
            <?php if (empty($topRatedBooks)): ?>
                <p style="color:#9ca3af;font-size:0.875rem">Chưa có dữ liệu.</p>
            <?php else: ?>
                <div style="display:flex;flex-direction:column;gap:0.75rem">
                    <?php foreach ($topRatedBooks as $book): ?>
                        <div class="admin-dashboard-list-item">
                            <div style="min-width:0;flex:1">
                                <p class="admin-dashboard-list-title"><?= e($book['title']) ?></p>
                                <p class="admin-dashboard-list-meta"><?= e($book['author']) ?> — <?= e($book['category_name'] ?? '') ?></p>
                            </div>
                            <div class="admin-dashboard-list-rating">
                                <p class="stars">★ <?= number_format($book['reviews_avg_rating'] ?? 0, 1) ?></p>
                                <p class="count"><?= (int)($book['reviews_count'] ?? 0) ?> đánh giá</p>
                            </div>
                        </div>
                    <?php endforeach ?>
                </div>
            <?php endif ?>
        </div>

        <!-- Latest Reviews -->
        <div class="admin-form-card">
            <h2 style="font-size:1.125rem;font-weight:600;color:#111827;margin-bottom:1rem">📝 Đánh giá mới nhất</h2>
            <?php if (empty($latestReviews)): ?>
                <p style="color:#9ca3af;font-size:0.875rem">Chưa có đánh giá.</p>
            <?php else: ?>
                <div style="display:flex;flex-direction:column;gap:0.75rem">
                    <?php foreach ($latestReviews as $review): ?>
                        <div class="admin-dashboard-review-item">
                            <div class="admin-dashboard-review-header">
                                <span class="admin-dashboard-review-user"><?= e($review['user_name'] ?? 'Unknown') ?></span>
                                <span class="admin-dashboard-review-date"><?= e(date('d/m/Y', strtotime($review['created_at']))) ?></span>
                            </div>
                            <p class="admin-dashboard-review-book">Sách: <?= e($review['book_title'] ?? '') ?></p>
                            <div style="font-size:0.875rem">
                                <?php for ($s = 1; $s <= 5; $s++): ?>
                                    <span style="color:<?= $s <= $review['rating'] ? '#f59e0b' : '#d1d5db' ?>">★</span>
                                <?php endfor ?>
                            </div>
                            <?php if (!empty($review['content'])): ?>
                                <p class="admin-dashboard-review-content"><?= e($review['content']) ?></p>
                            <?php endif ?>
                        </div>
                    <?php endforeach ?>
                </div>
            <?php endif ?>
        </div>
    </div>
</div>

<?php $content = ob_get_clean() ?>
<?php require __DIR__ . '/../layouts/admin.php' ?>
