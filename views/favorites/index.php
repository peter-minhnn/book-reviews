<?php $title = 'Yêu thích' ?>
<?php ob_start() ?>

<div class="user-page-header">
    <span class="badge badge-coral">❤️ Yêu thích</span>
    <h1 style="margin-top: 0.5rem;">Danh sách yêu thích</h1>
</div>

<?php if ($favorites->total() === 0): ?>
    <div class="empty-state">
        <div class="icon">💔</div>
        <p>Bạn chưa có sách yêu thích nào.</p>
        <a href="<?= route('books.index') ?>" class="user-btn-primary">Duyệt sách ngay</a>
    </div>
<?php else: ?>
    <div class="book-grid">
        <?php foreach ($favorites->items() as $book): ?>
            <div class="fav-card-wrapper">
                <?= view('components.book-card', ['book' => $book, 'showRating' => true]) ?>
                <form method="POST" action="<?= route('favorites.destroy', ['book' => $book['id']]) ?>">
                    <?= csrf_field() ?>
                    <?= method_field('DELETE') ?>
                    <button type="submit" class="fav-delete-btn" title="Xoá yêu thích" aria-label="Xoá yêu thích">✕</button>
                </form>
            </div>
        <?php endforeach ?>
    </div>

    <?php if ($favorites->hasPages()): ?>
        <div class="mt-8">
            <?= view('vendor.pagination.tailwind', ['paginator' => $favorites]) ?>
        </div>
    <?php endif ?>
<?php endif ?>

<?php $content = ob_get_clean() ?>
<?php require __DIR__ . '/../layouts/app.php' ?>
