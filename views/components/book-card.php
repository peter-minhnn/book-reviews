<?php
$showRating = $showRating ?? true;
$bookId = $book['id'];
$coverUrl = !empty($book['cover_image']) ? '/uploads/' . $book['cover_image'] : null;
?>
<div class="book-card">
    <a href="<?= route('books.show', ['book' => $bookId]) ?>" class="book-card-link">
        <div class="book-cover-wrap">
            <?php if ($coverUrl): ?>
                <img src="<?= e($coverUrl) ?>" alt="<?= e($book['title']) ?>"
                     class="book-cover-img" loading="lazy">
            <?php else: ?>
                <div class="book-cover-placeholder">📖</div>
            <?php endif ?>
            <span class="book-cover-badge"><?= e($book['category_name'] ?? 'Unknown') ?></span>
        </div>
        <div class="book-meta">
            <span class="book-meta-title"><?= e($book['title']) ?></span>
            <span class="book-meta-author"><?= e($book['author']) ?></span>
            <?php if ($showRating): ?>
                <div class="book-meta-rating">
                    <?php $rating = round($book['reviews_avg_rating'] ?? 0, 1) ?>
                    <span class="stars">
                        <?php for ($s = 1; $s <= 5; $s++): ?>
                            <span style="color: <?= $s <= round($rating) ? '#e5b73c' : '#d4c8b8' ?>;">★</span>
                        <?php endfor ?>
                    </span>
                    <span class="avg"><?= number_format($rating, 1) ?></span>
                    <span class="count">(<?= (int)($book['reviews_count'] ?? 0) ?>)</span>
                </div>
            <?php endif ?>
        </div>
    </a>
</div>
