<?php if ($paginator->hasPages()): ?>
<nav class="admin-pagination">
    <?php if (!$paginator->onFirstPage()): ?>
        <a href="<?= e($paginator->url(1)) ?>" class="admin-pagination-nav" title="Trang đầu">«</a>
    <?php else: ?>
        <span class="admin-pagination-disabled">«</span>
    <?php endif ?>

    <?php if (!$paginator->onFirstPage()): ?>
        <a href="<?= e($paginator->previousPageUrl()) ?>" class="admin-pagination-nav">←</a>
    <?php else: ?>
        <span class="admin-pagination-disabled">←</span>
    <?php endif ?>

    <?php for ($i = 1; $i <= $paginator->lastPage(); $i++): ?>
        <?php if ($i === $paginator->currentPage()): ?>
            <span class="admin-pagination-current"><?= $i ?></span>
        <?php else: ?>
            <a href="<?= e($paginator->url($i)) ?>" class="admin-pagination-link"><?= $i ?></a>
        <?php endif ?>
    <?php endfor ?>

    <?php if ($paginator->hasMorePages()): ?>
        <a href="<?= e($paginator->nextPageUrl()) ?>" class="admin-pagination-nav">→</a>
    <?php else: ?>
        <span class="admin-pagination-disabled">→</span>
    <?php endif ?>

    <?php if ($paginator->hasMorePages()): ?>
        <a href="<?= e($paginator->url($paginator->lastPage())) ?>" class="admin-pagination-nav" title="Trang cuối">»</a>
    <?php else: ?>
        <span class="admin-pagination-disabled">»</span>
    <?php endif ?>
</nav>
<?php endif ?>
