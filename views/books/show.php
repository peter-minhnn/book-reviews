<?php $title = e($book['title']) ?>
<?php ob_start() ?>

<div style="display: flex; flex-direction: column; gap: 2.5rem;">
    <!-- Book Detail Card -->
    <div class="clay overflow-hidden rounded-3xl" style="background: #fff;">
        <div class="md:flex">
            <div class="md:w-72 shrink-0 h-80 md:h-auto overflow-hidden" style="background: linear-gradient(135deg, #fef3e8 0%, #fee2d4 100%);">
                <?php if (!empty($book['cover_image'])): ?>
                    <img src="/uploads/<?= e($book['cover_image']) ?>" alt="<?= e($book['title']) ?>"
                         class="w-full h-full object-cover">
                <?php else: ?>
                    <div class="flex items-center justify-center h-full text-8xl">📖</div>
                <?php endif ?>
            </div>
            <div class="p-8 flex-1">
                <div class="flex items-start justify-between flex-wrap gap-4">
                    <div>
                        <span class="clay-sm inline-block px-3 py-1 text-xs font-semibold rounded-full mb-3" style="background: rgba(255,107,107,0.1); color: var(--coral);">
                            <?= e($book['category_name'] ?? '') ?>
                        </span>
                        <h1 class="font-display text-3xl md:text-4xl font-bold mb-2" style="color: var(--brown);"><?= e($book['title']) ?></h1>
                        <p class="text-lg" style="color: var(--brown-light);">bởi <?= e($book['author']) ?></p>
                        <?php if (!empty($book['published_year'])): ?>
                            <p class="text-sm mt-1" style="color: var(--brown-light);">Xuất bản: <?= e($book['published_year']) ?></p>
                        <?php endif ?>
                    </div>

                    <!-- Rating Summary -->
                    <div class="text-center clay-sm p-5 rounded-2xl" style="background: rgba(255,230,109,0.1);">
                        <div class="text-4xl font-display font-bold" style="color: #b8952e;"><?= number_format($averageRating, 1) ?></div>
                        <div class="flex items-center justify-center gap-0.5 text-lg mt-1">
                            <?php for ($s = 1; $s <= 5; $s++): ?>
                                <span style="color: <?= $s <= round($averageRating) ? 'var(--yellow)' : '#d4c8b8' ?>;">★</span>
                            <?php endfor ?>
                        </div>
                        <p class="text-sm mt-1" style="color: var(--brown-light);"><?= $reviewsCount ?> đánh giá</p>
                    </div>
                </div>

                <?php if (!empty($book['description'])): ?>
                    <div class="mt-6">
                        <h3 class="font-display font-semibold text-lg mb-2" style="color: var(--brown);">Mô tả</h3>
                        <p class="leading-relaxed" style="color: var(--brown-light);"><?= nl2br(e($book['description'])) ?></p>
                    </div>
                <?php endif ?>

                <!-- Favorite Button -->
                <?php if (auth()): ?>
                    <div class="mt-4">
                        <form method="POST" action="<?= $isFavorite ? route('favorites.destroy', ['book' => $book['id']]) : route('favorites.store', ['book' => $book['id']]) ?>" class="inline">
                            <?= csrf_field() ?>
                            <?php if ($isFavorite): ?>
                                <?= method_field('DELETE') ?>
                            <?php endif ?>
                            <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full font-semibold text-sm transition-all hover:-translate-y-0.5"
                                    style="<?= $isFavorite ? 'background: var(--coral); color: #fff; box-shadow: 0 4px 14px rgba(255,107,107,0.25);' : 'background: rgba(255,107,107,0.08); color: var(--coral);' ?>"
                                    aria-pressed="<?= $isFavorite ? 'true' : 'false' ?>">
                                <svg class="w-4 h-4" fill="<?= $isFavorite ? 'currentColor' : 'none' ?>" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                                <?= $isFavorite ? 'Đã yêu thích' : 'Thêm vào yêu thích' ?>
                            </button>
                        </form>
                    </div>
                <?php endif ?>
            </div>
        </div>
    </div>

    <!-- Review Section -->
    <div class="clay-sm p-8 rounded-3xl" style="background: #fff;">
        <h2 class="font-display text-2xl font-bold mb-6" style="color: var(--brown);">Đánh giá (<?= $reviewsCount ?>)</h2>

        <!-- Review Form -->
        <?php if (auth()): ?>
            <?php
                $isEditingReview = !empty($editingReview);
                $formRating = (int) old('rating', $isEditingReview ? $editingReview['rating'] : 0);
                $formContent = old('content', $isEditingReview ? ($editingReview['content'] ?? '') : '');
            ?>
            <div id="review-form" class="clay-sm clay-peach p-6 rounded-2xl mb-6" style="background: rgba(152,238,204,0.08);">
                <h3 class="font-display font-semibold mb-4" style="color: var(--brown);">
                    <?= $isEditingReview ? 'Cập nhật đánh giá' : 'Viết đánh giá của bạn' ?>
                </h3>
                <form method="POST" action="<?= $isEditingReview ? route('reviews.update', ['review' => $editingReview['id']]) : route('reviews.store', ['book' => $book['id']]) ?>">
                    <?= csrf_field() ?>
                    <?php if ($isEditingReview): ?>
                        <?= method_field('PUT') ?>
                    <?php endif ?>
                    <div class="mb-3" x-data="{ rating: <?= $formRating ?>, hover: 0 }">
                        <label class="block text-sm font-semibold mb-1.5" style="color: var(--brown);">Xếp hạng</label>
                        <div class="stars-row">
                            <?php $labels = [1 => 'Rất tệ', 2 => 'Tệ', 3 => 'Trung bình', 4 => 'Tốt', 5 => 'Rất tốt']; ?>
                            <?php for ($s = 1; $s <= 5; $s++): ?>
                                <span class="star-wrap">
                                    <button type="button" @click="rating = <?= $s ?>"
                                            @mouseenter="hover = <?= $s ?>" @mouseleave="hover = 0"
                                            class="star-btn" :class="(hover || rating) >= <?= $s ?> ? 'star-active' : 'star-inactive'">★</button>
                                    <span class="star-tooltip"><?= $labels[$s] ?></span>
                                </span>
                            <?php endfor ?>
                        </div>
                        <input type="hidden" name="rating" x-model="rating">
                        <?php if ($err = session('errors')['rating'][0] ?? ''): ?>
                            <p class="text-sm mt-1" style="color: #f87171;"><?= e($err) ?></p>
                        <?php endif ?>
                    </div>
                    <div class="mb-3">
                        <label class="block text-sm font-semibold mb-1.5" style="color: var(--brown);">Nội dung (tuỳ chọn)</label>
                        <textarea name="content" rows="1" maxlength="2000"
                                  placeholder="Chia sẻ cảm nhận của bạn về cuốn sách này..."
                                  x-data
                                  @input="$el.style.height = 'auto'; $el.style.height = ($el.scrollHeight) + 'px'"
                                  x-init="$el.style.height = 'auto'; $el.style.height = ($el.scrollHeight) + 'px'"
                                  class="w-full px-4 py-2.5 rounded-2xl text-sm outline-none transition-shadow focus:shadow-md resize-none overflow-hidden"
                                  style="border: 1.5px solid rgba(180,160,140,0.2); background: #fdfaf7; color: var(--brown);"><?= e($formContent) ?></textarea>
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="px-5 py-2.5 rounded-full font-display font-semibold text-sm text-white transition-all hover:-translate-y-0.5"
                                style="background: var(--coral); box-shadow: 0 4px 14px rgba(255,107,107,0.25);"><?= $isEditingReview ? 'Cập nhật' : 'Gửi đánh giá' ?></button>
                        <?php if ($isEditingReview): ?>
                            <a href="<?= route('books.show', ['book' => $book['id']]) ?>#reviews-list" class="px-5 py-2.5 rounded-full font-semibold text-sm transition-all"
                               style="background: rgba(74,55,40,0.06); color: var(--brown);">Huỷ</a>
                        <?php endif ?>
                    </div>
                </form>
            </div>
        <?php else: ?>
            <div class="text-center py-6 mb-6 clay-sm rounded-2xl" style="background: rgba(180,160,140,0.05);">
                <p style="color: var(--brown-light);"><a href="<?= route('login') ?>" style="color: var(--coral); font-weight: 600;">Đăng nhập</a> để viết đánh giá.</p>
            </div>
        <?php endif ?>

        <!-- Reviews List -->
        <div id="reviews-list"></div>
        <?php if (empty($reviews)): ?>
            <p class="text-center py-8" style="color: var(--brown-light);">Chưa có đánh giá nào. Hãy là người đầu tiên đánh giá!</p>
        <?php else: ?>
            <div class="space-y-4">
                <?php foreach ($reviews as $review): ?>
                    <div class="flex gap-4 p-5 rounded-2xl" style="background: #fdfaf7; border: 1px solid rgba(180,160,140,0.1);">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center shrink-0 text-white font-bold text-sm" style="background: linear-gradient(135deg, var(--coral), var(--brown));">
                            <?= e(mb_strtoupper(mb_substr($review['user_name'] ?? 'U', 0, 1))) ?>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between flex-wrap gap-2">
                                <div>
                                    <span class="font-display font-semibold text-sm" style="color: var(--brown);"><?= e($review['user_name'] ?? 'Unknown') ?></span>
                                    <div class="flex items-center gap-0.5 text-sm mt-0.5">
                                        <?php for ($s = 1; $s <= 5; $s++): ?>
                                            <span style="color: <?= $s <= $review['rating'] ? 'var(--yellow)' : '#d4c8b8' ?>;">★</span>
                                        <?php endfor ?>
                                        <span class="text-xs ml-2" style="color: var(--brown-light);"><?= e(timeAgo($review['created_at'])) ?></span>
                                    </div>
                                </div>
                                <?php if (auth() && ((int) auth()['id'] === (int) $review['user_id'] || (auth()['role'] ?? '') === 'admin')): ?>
                                    <div class="flex items-center gap-1.5">
                                        <?php if ((int) auth()['id'] === (int) $review['user_id']): ?>
                                            <a href="<?= route('books.show', ['book' => $book['id']]) ?>?edit_review=<?= $review['id'] ?>#review-form"
                                               class="inline-flex h-8 w-8 items-center justify-center rounded-full transition-all hover:-translate-y-0.5"
                                               style="color: var(--coral); background: rgba(255,107,107,0.08);"
                                               title="Sửa đánh giá" aria-label="Sửa đánh giá">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            </a>
                                        <?php endif ?>
                                        <button onclick="window.dispatchEvent(new CustomEvent('open-confirm', { detail: { action: '/reviews/<?= $review['id'] ?>', method: 'DELETE', message: 'Xoá đánh giá này?' } }))"
                                                class="inline-flex h-8 w-8 items-center justify-center rounded-full transition-all hover:-translate-y-0.5"
                                                style="color: #f87171; background: rgba(248,113,113,0.09);"
                                                title="Xoá đánh giá" aria-label="Xoá đánh giá">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </div>
                                <?php endif ?>
                            </div>
                            <?php if (!empty($review['content'])): ?>
                                <p class="text-sm mt-2 leading-relaxed" style="color: var(--brown-light);"><?= nl2br(e($review['content'])) ?></p>
                            <?php endif ?>
                        </div>
                    </div>
                <?php endforeach ?>
            </div>
        <?php endif ?>
    </div>
</div>

<?php $content = ob_get_clean() ?>
<?php require __DIR__ . '/../layouts/app.php' ?>
