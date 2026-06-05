<div class="sticky top-0 z-50 max-w-7xl mx-auto w-full px-4 pt-4">
<nav class="py-3 px-6 flex items-center justify-between" style="background: rgba(255,255,255,0.88); border-radius: 20px; box-shadow: 4px 4px 12px rgba(180,160,140,0.1), -2px -2px 8px rgba(255,255,255,0.9), inset 1px 1px 3px rgba(255,255,255,0.6); border: 1.5px solid rgba(255,255,255,0.7);">
    <a href="<?= route('home') ?>" class="flex items-center gap-2.5 group shrink-0">
        <span class="text-3xl animate-float">📚</span>
        <span class="font-display text-xl md:text-2xl font-bold tracking-tight" style="color: var(--coral);">Book<span style="color: var(--brown);">Review</span></span>
    </a>

    <div class="hidden md:flex items-center gap-1.5">
        <a href="<?= route('books.index') ?>" class="flex items-center gap-1.5 px-4 py-2 rounded-full font-semibold text-sm transition-all cursor-pointer
            <?= request()->routeIs('/books') ? 'text-white' : 'hover:bg-[#fff2ea]' ?>"
            style="<?= request()->routeIs('/books') ? 'background: var(--coral); box-shadow: 0 4px 14px rgba(255,107,107,0.25);' : 'color: var(--brown);' ?>">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            Duyệt sách
        </a>

        <!-- Autocomplete Search (desktop) -->
        <div class="hidden lg:flex items-center ml-2"
             x-data="searchAutocomplete()"
             @click.away="show = false"
             @keydown.escape.window="show = false; $refs.searchInput.blur()">
            <div class="relative" style="width: 340px;">
                <!-- Search icon -->
                <svg class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none z-10 transition-colors duration-200"
                     :style="show ? 'color: var(--coral);' : 'color: #c4b0a0;'"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <!-- Input -->
                <input type="text" x-ref="searchInput"
                       x-model="query"
                       @input.debounce.250ms="fetchSuggestions()"
                       @keydown.enter.prevent="submitSearch()"
                       @keydown.arrow-down.prevent="focusNext()"
                       @keydown.arrow-up.prevent="focusPrev()"
                       @focus="query.length >= 2 && fetchSuggestions()"
                       placeholder="Tìm kiếm sách, tác giả..."
                       class="w-full pl-10 pr-10 py-2.5 rounded-full outline-none text-sm transition-all duration-200"
                       :class="show ? 'shadow-lg' : 'shadow-sm'"
                       style="background:#fff; border: 1.5px solid rgba(180,160,140,0.2); color: var(--brown); box-shadow: 2px 2px 5px rgba(180,160,140,0.06), inset 1px 1px 3px rgba(255,255,255,0.6);">
                <!-- Clear button -->
                <button x-show="query.length > 0" x-cloak
                        @click="clearSearch()"
                        class="absolute right-2.5 top-1/2 -translate-y-1/2 z-10 w-6 h-6 rounded-full flex items-center justify-center transition-colors duration-150 hover:bg-[#ffe8e8]"
                        style="color: #c4b0a0;" title="Xoá">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
                <!-- Suggestions dropdown -->
                <div x-show="show && suggestions.length > 0" x-cloak
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 -translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 translate-y-0"
                     x-transition:leave-end="opacity-0 -translate-y-2"
                     class="absolute top-full left-0 right-0 mt-2 bg-white rounded-2xl shadow-2xl border border-[#e8d5c4]/30 overflow-hidden z-50"
                     style="max-height: 420px; overflow-y: auto;">
                    <!-- Header -->
                    <div class="px-4 py-2.5 bg-[#fdfaf7] border-b border-[#f5ebe0] flex items-center justify-between">
                        <span class="text-xs font-semibold" style="color: var(--brown-light);">
                            Kết quả cho "<span class="font-bold" style="color: var(--brown);" x-text="query"></span>"
                        </span>
                        <span class="text-xs" style="color: #c4b0a0;" x-text="suggestions.length + ' sách'"></span>
                    </div>
                    <!-- Results -->
                    <template x-for="(item, idx) in suggestions" :key="idx">
                        <div @click="goToBook(item.id)"
                             @mouseenter="activeIdx = idx"
                             :class="activeIdx === idx ? 'bg-[#fff2ea]' : ''"
                             class="flex items-center gap-3 px-4 py-3 cursor-pointer border-b border-[#f5ebe0] last:border-b-0 transition-all duration-200 ease-out hover:bg-[#fdf6f0] hover:pl-5"
                             style="border-left: 3px solid transparent;"
                             :style="activeIdx === idx ? 'border-left-color: var(--coral);' : 'border-left-color: transparent;'">
                            <!-- Cover thumbnail -->
                            <div class="shrink-0 w-10 h-14 rounded-md overflow-hidden shadow-sm" style="background: linear-gradient(135deg, #fef3e8, #fee2d4);">
                                <template x-if="item.cover">
                                    <img :src="item.cover" :alt="item.title"
                                         class="w-full h-full object-cover">
                                </template>
                                <template x-if="!item.cover">
                                    <div class="w-full h-full flex items-center justify-center text-lg">📖</div>
                                </template>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-sm font-semibold truncate leading-tight" style="color: var(--brown);" x-text="item.title"></p>
                                <p class="text-xs truncate mt-0.5 leading-tight" style="color: var(--brown-light);" x-text="item.author"></p>
                            </div>
                            <span class="shrink-0 text-xs px-2 py-1 rounded-full font-medium" style="background: rgba(255,107,107,0.07); color: var(--coral);" x-text="item.category"></span>
                        </div>
                    </template>
                    <!-- Footer -->
                    <div @click="submitSearch()"
                         class="px-4 py-3 text-center cursor-pointer transition-colors duration-150 hover:bg-[#fdf6f0] border-t border-[#f5ebe0]">
                        <span class="text-sm font-semibold" style="color: var(--coral);">
                            Xem tất cả kết quả cho "<span x-text="query" style="font-weight:700;"></span>" →
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <?php if (auth()): ?>
            <a href="<?= route('favorites.index') ?>" class="flex items-center gap-1.5 px-4 py-2 rounded-full font-semibold text-sm transition-all cursor-pointer
                <?= request()->routeIs('/favorites') ? 'text-white' : 'hover:bg-[#ffe8e8]' ?>"
                style="<?= request()->routeIs('/favorites') ? 'background: var(--coral); box-shadow: 0 4px 14px rgba(255,107,107,0.25);' : 'color: var(--brown);' ?>">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                Yêu thích
            </a>
            <a href="<?= route('profile.edit') ?>" class="flex items-center gap-1.5 px-4 py-2 rounded-full font-semibold text-sm transition-all hover:bg-[#fff2ea] cursor-pointer" style="color: var(--brown);">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                Tài khoản
            </a>
            <?php if ((auth()['role'] ?? '') === 'admin'): ?>
                <a href="<?= route('admin.dashboard') ?>" class="flex items-center gap-1.5 px-4 py-2 rounded-full font-semibold text-sm transition-all hover:bg-[#f3eeff] cursor-pointer" style="color: #7c3aed;">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Admin
                </a>
            <?php endif ?>
            <form method="POST" action="<?= route('logout') ?>">
                <?= csrf_field() ?>
                <button type="submit" class="flex items-center gap-1.5 px-4 py-2 rounded-full font-semibold text-sm transition-all hover:bg-[#ffe8e8] cursor-pointer" style="color: var(--brown-light);">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    Đăng xuất
                </button>
            </form>
        <?php else: ?>
            <a href="<?= route('login') ?>" class="flex items-center gap-1.5 px-4 py-2 rounded-full font-semibold text-sm transition-all hover:bg-[#fff2ea] cursor-pointer" style="color: var(--brown);">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                Đăng nhập
            </a>
            <a href="<?= route('register') ?>" class="flex items-center gap-1.5 px-5 py-2 rounded-full font-display font-semibold text-sm text-white pulse-cta cursor-pointer" style="background: var(--coral); box-shadow: 0 4px 14px rgba(255,107,107,0.35);">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                Đăng ký
            </a>
        <?php endif ?>
    </div>

    <button id="mobileMenuBtn" class="md:hidden text-2xl p-2" onclick="document.getElementById('mobileMenu').toggleAttribute('hidden')" aria-label="Menu">☰</button>
</nav>
</div>

<div id="mobileMenu" hidden class="md:hidden sticky top-24 z-40 mx-4 mb-4 py-4 px-6 flex flex-col gap-2 font-semibold text-sm" style="background: #fff; border-radius: 20px; box-shadow: 4px 4px 12px rgba(180,160,140,0.15), -2px -2px 8px rgba(255,255,255,0.9); border: 1.5px solid rgba(180,160,140,0.15);">
    <!-- Mobile search -->
    <form method="GET" action="<?= route('books.index') ?>" class="mb-1" onsubmit="document.getElementById('mobileMenu').setAttribute('hidden','')">
        <input type="text" name="search" value="<?= e($_GET['search'] ?? '') ?>"
               placeholder="Tìm kiếm sách..."
               class="w-full pl-9 pr-4 py-2.5 rounded-full text-sm outline-none"
               style="background:#fff; border: 1.5px solid rgba(180,160,140,0.2); color: var(--brown);">
    </form>
    <a href="<?= route('books.index') ?>" class="py-2.5 px-4 rounded-full cursor-pointer <?= request()->routeIs('/books') ? 'text-white' : '' ?>"
       style="<?= request()->routeIs('/books') ? 'background: var(--coral);' : 'color: var(--brown);' ?>"
       onmouseover="if(!this.classList.contains('text-white'))this.style.background='#fff2ea'" onmouseout="if(!this.classList.contains('text-white'))this.style.background='transparent'">🔍 Duyệt sách</a>
    <?php if (auth()): ?>
        <a href="<?= route('favorites.index') ?>" class="py-2.5 px-4 rounded-full cursor-pointer <?= request()->routeIs('/favorites') ? 'text-white' : '' ?>"
           style="<?= request()->routeIs('/favorites') ? 'background: var(--coral);' : 'color: var(--brown);' ?>"
           onmouseover="if(!this.classList.contains('text-white'))this.style.background='#ffe8e8'" onmouseout="if(!this.classList.contains('text-white'))this.style.background='transparent'">❤️ Yêu thích</a>
        <a href="<?= route('profile.edit') ?>" class="py-2.5 px-4 rounded-full cursor-pointer" style="color: var(--brown);"
           onmouseover="this.style.background='#fff2ea'" onmouseout="this.style.background='transparent'">👤 Tài khoản</a>
        <?php if ((auth()['role'] ?? '') === 'admin'): ?>
            <a href="<?= route('admin.dashboard') ?>" class="py-2.5 px-4 rounded-full cursor-pointer" style="color: #7c3aed;"
               onmouseover="this.style.background='#f3eeff'" onmouseout="this.style.background='transparent'">⚙️ Admin</a>
        <?php endif ?>
        <form method="POST" action="<?= route('logout') ?>">
            <?= csrf_field() ?>
            <button type="submit" class="w-full text-left py-2.5 px-4 rounded-full cursor-pointer" style="color: var(--brown-light);"
                    onmouseover="this.style.background='#ffe8e8'" onmouseout="this.style.background='transparent'">🚪 Đăng xuất</button>
        </form>
    <?php else: ?>
        <a href="<?= route('login') ?>" class="py-2.5 px-4 rounded-full cursor-pointer" style="color: var(--brown);"
           onmouseover="this.style.background='#fff2ea'" onmouseout="this.style.background='transparent'">🔑 Đăng nhập</a>
        <a href="<?= route('register') ?>" class="py-2.5 px-4 rounded-full text-white text-center cursor-pointer" style="background: var(--coral); box-shadow: 0 4px 14px rgba(255,107,107,0.35);">✨ Đăng ký</a>
    <?php endif ?>
</div>

<script>
    document.querySelectorAll('#mobileMenu a').forEach(link => {
        link.addEventListener('click', () => {
            document.getElementById('mobileMenu').setAttribute('hidden', '');
        });
    });

    // Autocomplete search (global Alpine component)
    document.addEventListener('alpine:init', () => {
        Alpine.data('searchAutocomplete', () => ({
            query: '<?= e($_GET['search'] ?? '') ?>',
            suggestions: [],
            show: false,
            activeIdx: -1,
            controller: null,

            async fetchSuggestions() {
                if (this.query.length < 2) {
                    this.suggestions = [];
                    this.show = false;
                    return;
                }
                if (this.controller) this.controller.abort();
                this.controller = new AbortController();
                try {
                    const resp = await fetch(
                        '/books/search?q=' + encodeURIComponent(this.query),
                        { headers: { 'X-Requested-With': 'XMLHttpRequest' }, signal: this.controller.signal }
                    );
                    if (!resp.ok) throw new Error('Network error');
                    const html = await resp.text();
                    // Parse book titles from the grid HTML
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const cards = doc.querySelectorAll('.book-card');
                    this.suggestions = Array.from(cards).slice(0, 6).map(card => ({
                        id: card.querySelector('a')?.href?.match(/\/books\/(\d+)/)?.[1] || '',
                        title: card.querySelector('.book-meta-title')?.textContent?.trim() || '',
                        author: card.querySelector('.book-meta-author')?.textContent?.trim() || '',
                        category: card.querySelector('.book-cover-badge')?.textContent?.trim() || '',
                        cover: card.querySelector('.book-cover-img')?.getAttribute('src') || '',
                    }));
                    this.show = this.suggestions.length > 0;
                    this.activeIdx = -1;
                } catch (e) {
                    if (e.name !== 'AbortError') {
                        this.suggestions = [];
                        this.show = false;
                    }
                }
            },

            clearSearch() {
                this.query = '';
                this.suggestions = [];
                this.show = false;
                this.activeIdx = -1;
            },

            submitSearch() {
                this.show = false;
                window.location.href = '/books?search=' + encodeURIComponent(this.query);
            },

            goToBook(id) {
                this.show = false;
                window.location.href = '/books/' + id;
            },

            focusNext() {
                if (this.suggestions.length === 0) return;
                this.activeIdx = (this.activeIdx + 1) % this.suggestions.length;
            },

            focusPrev() {
                if (this.suggestions.length === 0) return;
                this.activeIdx = this.activeIdx <= 0 ? this.suggestions.length - 1 : this.activeIdx - 1;
            },
        }));
    });
</script>
