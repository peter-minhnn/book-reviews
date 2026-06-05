<?php $title = 'Duyệt sách' ?>
<?php ob_start() ?>

<div x-data="bookBrowser()" x-init="init()" class="space-y-6">
    <!-- Filters -->
    <div class="filter-bar">
        <div class="filter-group search">
            <label class="filter-label">Tìm kiếm</label>
            <input type="text" x-model="search" @input.debounce.300ms="fetchBooks()"
                   placeholder="Tên sách hoặc tác giả..."
                   class="filter-input">
        </div>
        <div class="filter-group select">
            <label class="filter-label">Danh mục</label>
            <select x-model="category" @change="fetchBooks()" class="filter-select">
                <option value="">Tất cả danh mục</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>"><?= e($cat['name']) ?></option>
                <?php endforeach ?>
            </select>
        </div>
        <div class="filter-group select">
            <label class="filter-label">Sắp xếp</label>
            <select x-model="sort" @change="fetchBooks()" class="filter-select">
                <option value="newest">Mới nhất</option>
                <option value="rating">Đánh giá cao</option>
            </select>
        </div>
    </div>

    <!-- Book Grid -->
    <div x-ref="grid" id="book-grid">
        <div class="loading-state">
            <div class="spinner"></div>
            Đang tải sách...
        </div>
    </div>
</div>

<script>
function bookBrowser() {
    return {
        search: '<?= e($_GET['search'] ?? '') ?>',
        category: '<?= e($_GET['category'] ?? '') ?>',
        sort: 'newest',

        init() {
            this.fetchBooks();
        },

        async fetchBooks() {
            var grid = this.$refs.grid;
            var params = new URLSearchParams();
            if (this.search) params.set('search', this.search);
            if (this.category) params.set('category', this.category);
            if (this.sort) params.set('sort', this.sort);
            params.set('page', '1');

            var url = '/books?' + params.toString();

            // Update browser URL
            if (window.history && window.history.pushState) {
                window.history.pushState({search: this.search, category: this.category, sort: this.sort}, '', url);
            }

            try {
                var resp = await fetch(url, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                var html = await resp.text();
                grid.innerHTML = html;
            } catch (e) {
                grid.innerHTML = '<div class="book-grid-empty" style="color: #f87171;">Có lỗi xảy ra khi tải sách.</div>';
            }
        }
    }
}
</script>

<?php $content = ob_get_clean() ?>
<?php require __DIR__ . '/../layouts/app.php' ?>
