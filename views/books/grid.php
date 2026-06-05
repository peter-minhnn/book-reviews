<div class="book-grid">
    <?php if ($books->total() === 0): ?>
        <div class="book-grid-empty">Không tìm thấy sách nào.</div>
    <?php else: ?>
        <?php foreach ($books->items() as $book): ?>
            <?= view('components.book-card', ['book' => $book, 'showRating' => true]) ?>
        <?php endforeach ?>
    <?php endif ?>
</div>

<?php if ($books->hasPages()): ?>
    <div class="mt-8">
        <?= view('vendor.pagination.tailwind', ['paginator' => $books]) ?>
    </div>
<?php endif ?>
