<?php $title = 'Trang chủ' ?>
<?php ob_start() ?>

<!-- Hero Section -->
<section class="relative book-bg rounded-3xl p-8 md:p-14 mb-14 text-center overflow-hidden reveal" style="background: var(--brown);">
    <span class="sparkle sparkle-1 absolute text-xl" style="top:10%;left:8%;">📚</span>
    <span class="sparkle sparkle-2 absolute text-2xl" style="top:20%;right:12%;">⭐</span>
    <span class="sparkle sparkle-3 absolute text-lg" style="bottom:25%;left:15%;">💫</span>
    <div class="relative z-10 max-w-2xl mx-auto">
        <div class="inline-flex items-center gap-2 clay-sm px-5 py-2 rounded-full text-sm font-semibold mb-6 reveal-1" style="background: rgba(255,255,255,0.1); color: var(--peach); border-color: rgba(255,255,255,0.1);">
            <span>📖</span> Khám phá sách hay <span style="color: var(--yellow);">★</span>
        </div>
        <h1 class="font-display text-4xl md:text-6xl font-bold leading-tight mb-6 reveal-2" style="color: white;">
            Đọc &amp; Chia Sẻ<br>
            <span style="color: var(--coral);">Cảm Nhận</span> Của Bạn
        </h1>
        <p class="text-lg mb-10 leading-relaxed reveal-3" style="color: #c4b0a0; max-width: 460px; margin: 0 auto 2.5rem;">
            Khám phá những cuốn sách hay, chia sẻ cảm nhận và tìm kiếm cuốn sách tiếp theo cho bạn.
        </p>
        <div class="flex flex-wrap gap-4 justify-center reveal-4">
            <a href="<?= route('books.index') ?>"
               class="inline-flex items-center gap-2 px-8 py-3.5 rounded-full font-display font-bold text-lg text-white pulse-cta" style="background: var(--coral); box-shadow: 0 6px 24px rgba(255,107,107,0.4);">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                Duyệt sách
            </a>
            <?php if (!auth()): ?>
                <a href="<?= route('register') ?>"
                   class="inline-flex items-center gap-2 px-8 py-3.5 rounded-full font-display font-bold text-lg transition-all" style="background: var(--yellow); color: var(--brown); box-shadow: 0 6px 24px rgba(255,230,109,0.4);">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                    Tham gia ngay
                </a>
            <?php endif ?>
        </div>
    </div>
</section>

<!-- Latest Books -->
<section class="mb-14">
    <div class="flex items-center justify-between mb-8">
        <div class="reveal">
            <span class="inline-flex items-center gap-1.5 clay-sm px-4 py-1.5 rounded-full text-sm font-semibold mb-3" style="background: rgba(152,238,204,0.2); color: var(--mint-deep);">
                📚 Mới cập nhật
            </span>
            <h2 class="font-display text-3xl md:text-4xl font-bold" style="color: var(--brown);">Sách mới nhất</h2>
        </div>
        <a href="<?= route('books.index') ?>" class="hidden sm:flex items-center gap-1.5 font-display font-semibold text-sm px-5 py-2.5 rounded-full transition-all hover:-translate-y-0.5" style="color: var(--coral); background: rgba(255,107,107,0.08);">Xem tất cả →</a>
    </div>
    <div class="book-grid">
        <?php if (empty($latestBooks)): ?>
            <div class="book-grid-empty">Không tìm thấy sách nào.</div>
        <?php else: ?>
            <?php foreach ($latestBooks as $book): ?>
                <?= view('components.book-card', ['book' => $book, 'showRating' => false]) ?>
            <?php endforeach ?>
        <?php endif ?>
    </div>
</section>

<!-- Live Reviews (SSE) -->
<section class="mb-14">
    <div class="mb-8 reveal">
        <span class="inline-flex items-center gap-1.5 clay-sm px-4 py-1.5 rounded-full text-sm font-semibold mb-3" style="background: rgba(255,107,107,0.12); color: var(--coral);">
            <span id="sse-status-dot" class="w-2 h-2 rounded-full inline-block" style="background: var(--mint-deep);"></span> Trực tiếp
        </span>
        <h2 class="font-display text-3xl md:text-4xl font-bold" style="color: var(--brown);">Đánh giá mới nhất</h2>
    </div>
    <div id="live-reviews" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
        <div class="col-span-full text-center py-8 reveal" style="color: var(--brown-light);">
            <div class="inline-flex items-center gap-2">
                <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                Đang kết nối luồng đánh giá trực tiếp...
            </div>
        </div>
    </div>
</section>

<!-- Top Rated Books -->
<section class="mb-14">
    <div class="mb-8 reveal">
        <span class="inline-flex items-center gap-1.5 clay-sm px-4 py-1.5 rounded-full text-sm font-semibold mb-3" style="background: rgba(255,230,109,0.25); color: #b8952e;">
            ⭐ Đánh giá cao
        </span>
        <h2 class="font-display text-3xl md:text-4xl font-bold" style="color: var(--brown);">Đánh giá cao nhất</h2>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
        <?php $i = 0; foreach ($topRatedBooks as $book): $i++; ?>
            <div class="clay-sm clay-yellow p-5 flex items-center gap-4 reveal reveal-<?= $i ?>">
                <div class="shrink-0 w-12 h-12 rounded-xl flex items-center justify-center text-xl" style="background: var(--yellow); color: var(--brown);">📖</div>
                <div class="min-w-0">
                    <a href="<?= route('books.show', ['book' => $book['id']]) ?>"
                       class="font-display font-semibold line-clamp-1 hover:opacity-80 transition-opacity" style="color: var(--brown);">
                        <?= e($book['title']) ?>
                    </a>
                    <p class="text-sm mt-0.5" style="color: var(--brown-light);"><?= e($book['author']) ?></p>
                    <p class="text-sm font-bold mt-1 flex items-center gap-1" style="color: #b8952e;">
                        ★ <?= number_format($book['reviews_avg_rating'] ?? 0, 1) ?> / 5
                    </p>
                </div>
            </div>
        <?php endforeach ?>
    </div>
</section>

<?php $content = ob_get_clean() ?>
<?php require __DIR__ . '/layouts/app.php' ?>

<!-- SSE Script -->
<script>
(function () {
    var container = document.getElementById('live-reviews');
    var statusDot = document.getElementById('sse-status-dot');
    var renderedIds = {};
    var maxCards = 6;
    var receivedFirstBatch = false;

    function escapeHtml(str) {
        var div = document.createElement('div');
        div.appendChild(document.createTextNode(str));
        return div.innerHTML;
    }

    function buildStars(rating) {
        var wrapper = document.createElement('span');
        wrapper.className = 'text-sm flex items-center gap-0.5';
        for (var i = 1; i <= 5; i++) {
            var star = document.createElement('span');
            star.textContent = '★';
            star.style.color = i <= rating ? 'var(--yellow)' : '#d4c8b8';
            wrapper.appendChild(star);
        }
        var num = document.createElement('span');
        num.className = 'ml-1 font-bold';
        num.style.color = 'var(--brown)';
        num.textContent = rating + '/5';
        wrapper.appendChild(num);
        return wrapper;
    }

    function addCard(review) {
        if (renderedIds[review.id]) return;
        renderedIds[review.id] = true;

        if (!receivedFirstBatch) {
            receivedFirstBatch = true;
            var loader = container.querySelector('.col-span-full');
            if (loader) loader.remove();
        }

        var card = document.createElement('div');
        card.className = 'clay-sm clay-peach p-5 flex flex-col gap-2 reveal';

        var header = document.createElement('div');
        header.className = 'flex items-center justify-between';
        var reviewer = document.createElement('span');
        reviewer.className = 'font-display font-semibold text-sm';
        reviewer.style.color = 'var(--brown)';
        reviewer.textContent = review.reviewer;
        var ts = document.createElement('span');
        ts.className = 'text-xs';
        ts.style.color = 'var(--brown-light)';
        ts.textContent = review.timestamp;
        header.appendChild(reviewer);
        header.appendChild(ts);

        var link = document.createElement('a');
        link.href = '/books/' + review.book_id;
        link.className = 'font-display font-bold line-clamp-1 hover:opacity-80 transition-opacity';
        link.style.color = 'var(--coral)';
        link.textContent = review.book_title;

        var stars = buildStars(review.rating);

        var content = document.createElement('p');
        content.className = 'text-sm line-clamp-2';
        content.style.color = 'var(--brown-light)';
        content.textContent = review.content || '';

        card.appendChild(header);
        card.appendChild(link);
        card.appendChild(stars);
        card.appendChild(content);

        container.insertBefore(card, container.firstChild);

        var cards = container.querySelectorAll('.clay-sm');
        if (cards.length > maxCards) {
            cards[cards.length - 1].remove();
            var keys = Object.keys(renderedIds);
            if (keys.length > 0) delete renderedIds[keys[keys.length - 1]];
        }
    }

    function setStatus(state) {
        if (!statusDot) return;
        if (state === 'connected') {
            statusDot.style.background = 'var(--mint-deep)';
        } else {
            statusDot.style.background = '#f87171';
        }
    }

    function connectSSE() {
        var source = new EventSource('/events/latest-reviews');
        source.addEventListener('reviews', function (e) {
            var reviews = JSON.parse(e.data);
            if (!receivedFirstBatch && reviews.length === 0) {
                container.innerHTML = '<div class="col-span-full text-center py-8 reveal" style="color: var(--brown-light);">Chưa có đánh giá mới.</div>';
                receivedFirstBatch = true;
                return;
            }
            reviews.forEach(function (r) { addCard(r); });
        });
        source.addEventListener('open', function () { setStatus('connected'); });
        source.addEventListener('error', function () {
            setStatus('error');
            source.close();
            if (!receivedFirstBatch) {
                container.innerHTML = '<div class="col-span-full text-center py-8 reveal" style="color: #f87171;">Mất kết nối tới luồng đánh giá. Đang thử lại...</div>';
            }
            setTimeout(connectSSE, 5000);
        });
    }

    connectSSE();
})();
</script>
