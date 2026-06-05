<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?= csrf_token() ?>">

    <title><?= isset($title) ? e($title) . ' - ' . config('app.name') : config('app.name') ?></title>
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">

    <!-- Fonts: Playfair Display (headings) + Be Vietnam Pro (body, optimized for Vietnamese) -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=be-vietnam-pro:400,500,600,700|playfair-display:600,700,800&display=swap" rel="stylesheet" />

    <!-- Styles -->
    <link rel="stylesheet" href="<?= vite_asset('resources/css/app.css') ?>">
    <link rel="stylesheet" href="/css/ui.css">
    <script defer src="<?= vite_asset('resources/js/app.js') ?>"></script>
    <style>
        /* ── Global ── */
        button { cursor: pointer; }

        /* ── CSS Variables ── */
        :root {
            --brown: #4a3728;
            --brown-light: #8b7355;
            --coral: #ff6b6b;
            --coral-light: rgba(255,107,107,0.08);
            --peach: #f5d0c5;
            --yellow: #ffe66d;
            --mint: #98eecc;
            --mint-deep: #2d8a6e;
            --bg: #fcf9f5;
            --bg-card: #ffffff;
            --border: rgba(180,160,140,0.2);
            --text-muted: #a08c7c;
        }

        /* ── User Page Header ── */
        .user-page-header {
            margin-bottom: 2rem;
        }
        .user-page-header .badge {
            display: inline-flex; align-items: center; gap: 0.375rem;
            padding: 0.375rem 1rem;
            border-radius: 9999px;
            font-size: 0.875rem; font-weight: 600;
        }
        .user-page-header .badge-coral {
            background: rgba(255,107,107,0.12); color: var(--coral);
        }
        .user-page-header .badge-mint {
            background: rgba(152,238,204,0.2); color: var(--mint-deep);
        }
        .user-page-header .badge-yellow {
            background: rgba(255,230,109,0.25); color: #b8952e;
        }
        .user-page-header h1 {
            font-family: 'Be Vietnam Pro', sans-serif;
            font-size: 1.875rem; font-weight: 700;
            color: var(--brown);
        }
        @media (min-width: 768px) {
            .user-page-header h1 { font-size: 2.25rem; }
        }

        /* ── User Cards ── */
        .user-card {
            background: var(--bg-card);
            border-radius: 1.5rem;
            padding: 2rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        }

        .user-form-card {
            background: var(--bg-card);
            border-radius: 1.5rem;
            padding: 2rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        }
        .user-form-card h2 {
            font-family: 'Be Vietnam Pro', sans-serif;
            font-size: 1.25rem; font-weight: 700;
            color: var(--brown);
            margin-bottom: 1.5rem;
        }

        /* ── User Form Inputs ── */
        .user-input {
            width: 100%; padding: 0.625rem 1.25rem;
            border: 1.5px solid var(--border);
            border-radius: 9999px;
            font-size: 0.875rem; color: var(--brown);
            background: #fdfaf7; outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
            box-sizing: border-box;
        }
        .user-input:focus {
            border-color: rgba(255,107,107,0.4);
            box-shadow: 0 0 0 3px rgba(255,107,107,0.08);
        }
        .user-input::placeholder { color: var(--text-muted); }
        .user-input.danger {
            border-color: rgba(255,107,107,0.3);
        }
        .user-input.danger:focus {
            border-color: rgba(255,107,107,0.5);
            box-shadow: 0 0 0 3px rgba(255,107,107,0.1);
        }

        .user-label {
            display: block;
            font-size: 0.875rem; font-weight: 600;
            color: var(--brown);
            margin-bottom: 0.375rem;
        }
        .user-form-error {
            font-size: 0.8125rem; color: #f87171;
            margin-top: 0.25rem;
        }
        .user-form-group {
            margin-bottom: 1rem;
        }

        /* ── User Buttons ── */
        .user-btn-primary {
            display: inline-flex; align-items: center; gap: 0.5rem;
            padding: 0.625rem 1.5rem;
            background: var(--coral); color: #fff;
            font-size: 0.875rem; font-weight: 600;
            border: none; border-radius: 9999px;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s;
            box-shadow: 0 4px 14px rgba(255,107,107,0.25);
            font-family: 'Be Vietnam Pro', sans-serif;
        }
        .user-btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(255,107,107,0.35);
        }

        .user-btn-secondary {
            display: inline-flex; align-items: center; gap: 0.5rem;
            padding: 0.625rem 1.5rem;
            background: rgba(74,55,40,0.06); color: var(--brown);
            font-size: 0.875rem; font-weight: 600;
            border: none; border-radius: 9999px;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s;
        }
        .user-btn-secondary:hover {
            background: rgba(74,55,40,0.1);
            transform: translateY(-1px);
        }

        .user-btn-danger {
            display: inline-flex; align-items: center; gap: 0.5rem;
            padding: 0.625rem 1.5rem;
            background: #f87171; color: #fff;
            font-size: 0.875rem; font-weight: 600;
            border: none; border-radius: 9999px;
            cursor: pointer;
            transition: all 0.2s;
            box-shadow: 0 4px 14px rgba(248,113,113,0.3);
            font-family: 'Be Vietnam Pro', sans-serif;
        }
        .user-btn-danger:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(248,113,113,0.4);
        }

        /* ── Book Grid ── */
        .book-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.25rem;
        }
        @media (min-width: 640px) {
            .book-grid { grid-template-columns: repeat(2, 1fr); gap: 1.5rem; }
        }
        @media (min-width: 768px) {
            .book-grid { grid-template-columns: repeat(3, 1fr); }
        }
        @media (min-width: 1024px) {
            .book-grid { grid-template-columns: repeat(4, 1fr); }
        }
        @media (min-width: 1280px) {
            .book-grid { grid-template-columns: repeat(5, 1fr); }
        }

        .book-grid-empty {
            grid-column: 1 / -1;
            text-align: center;
            padding: 3rem 0;
            color: var(--brown-light);
        }

        /* ── Book Card ── */
        .book-card {
            background: #fff;
            border-radius: 1rem;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04), 0 1px 2px rgba(0,0,0,0.03);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            display: flex;
            flex-direction: column;
            height: 100%;
        }
        .book-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px rgba(0,0,0,0.08);
        }
        .book-card-link {
            display: flex; flex-direction: column;
            height: 100%;
            text-decoration: none; color: inherit;
        }

        .book-cover-wrap {
            position: relative;
            width: 100%;
            aspect-ratio: 2 / 3;
            overflow: hidden;
            background: linear-gradient(135deg, #fef3e8 0%, #fee2d4 100%);
        }
        .book-cover-img {
            width: 100%; height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }
        .book-card:hover .book-cover-img {
            transform: scale(1.05);
        }
        .book-cover-placeholder {
            display: flex; align-items: center; justify-content: center;
            width: 100%; height: 100%;
            font-size: 3.5rem;
        }
        .book-cover-badge {
            position: absolute; top: 0.75rem; right: 0.75rem;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem; font-weight: 600;
            background: rgba(255,255,255,0.92);
            color: var(--brown);
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
            z-index: 2;
        }

        .book-meta {
            padding: 0.875rem 1rem 1rem;
            flex: 1;
            display: flex; flex-direction: column;
        }
        .book-meta-title {
            font-family: 'Be Vietnam Pro', sans-serif;
            font-weight: 700; font-size: 0.9375rem;
            color: var(--brown);
            line-height: 1.3;
            overflow: hidden; text-overflow: ellipsis;
            display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;
        }
        .book-meta-author {
            font-size: 0.8125rem; color: var(--brown-light);
            margin-top: 0.25rem;
            overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
        }
        .book-meta-rating {
            display: flex; align-items: center; gap: 0.375rem;
            margin-top: auto; padding-top: 0.5rem;
        }
        .book-meta-rating .stars {
            display: flex; align-items: center; gap: 0.125rem;
            font-size: 0.875rem;
        }
        .book-meta-rating .avg {
            font-size: 0.8125rem; font-weight: 700; color: var(--brown);
        }
        .book-meta-rating .count {
            font-size: 0.75rem; color: var(--brown-light);
        }

        /* ── Profile Layout ── */
        .profile-layout {
            display: grid;
            grid-template-columns: 1fr;
            gap: 2rem;
        }
        @media (min-width: 768px) {
            .profile-layout {
                grid-template-columns: 280px 1fr;
            }
        }

        .profile-sidebar {
            display: flex; flex-direction: column; gap: 1.5rem;
        }

        .profile-summary {
            background: #fff;
            border-radius: 1.5rem;
            padding: 1.5rem;
            text-align: center;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        }
        .profile-avatar {
            width: 4.5rem; height: 4.5rem;
            border-radius: 1.25rem;
            background: linear-gradient(135deg, var(--coral), #e55a5a);
            display: flex; align-items: center; justify-content: center;
            font-size: 2rem; font-weight: 700; color: #fff;
            margin: 0 auto 1rem;
            box-shadow: 0 6px 16px rgba(255,107,107,0.25);
        }
        .profile-name {
            font-family: 'Be Vietnam Pro', sans-serif;
            font-size: 1.25rem; font-weight: 700;
            color: var(--brown);
        }
        .profile-email {
            font-size: 0.875rem; color: var(--brown-light);
        }
        .profile-role-badge {
            display: inline-block;
            margin-top: 0.75rem;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem; font-weight: 600;
        }
        .profile-role-badge.admin {
            background: #f3e8ff; color: #6b21a8;
        }
        .profile-role-badge.user {
            background: rgba(74,55,40,0.06); color: var(--brown);
        }

        .profile-section {
            background: #fff;
            border-radius: 1.5rem;
            padding: 2rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        }
        .profile-section h2 {
            font-family: 'Be Vietnam Pro', sans-serif;
            font-size: 1.25rem; font-weight: 700;
            color: var(--brown);
            margin-bottom: 1.5rem;
        }

        /* ── Danger Zone ── */
        .danger-zone {
            background: #fff;
            border: 1.5px solid rgba(255,107,107,0.2);
            border-radius: 1.5rem;
            padding: 1.5rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        }
        .danger-zone h3 {
            font-family: 'Be Vietnam Pro', sans-serif;
            font-size: 1rem; font-weight: 700;
            color: #f87171;
            margin-bottom: 0.5rem;
        }
        .danger-zone p {
            font-size: 0.8125rem; color: var(--brown-light);
            margin-bottom: 1rem;
            line-height: 1.5;
        }

        /* ── Filter Bar ── */
        .filter-bar {
            background: #fff;
            border-radius: 1rem;
            padding: 1.25rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
            display: flex; flex-wrap: wrap; gap: 1rem;
            align-items: flex-end;
        }
        .filter-bar .filter-group {
            display: flex; flex-direction: column; gap: 0.375rem;
            min-width: 0;
        }
        .filter-bar .filter-group.search { flex: 2; min-width: 180px; }
        .filter-bar .filter-group.select { flex: 1; min-width: 140px; }
        .filter-bar .filter-label {
            font-size: 0.8125rem; font-weight: 600;
            color: var(--brown);
            white-space: nowrap;
        }
        .filter-input {
            width: 100%; padding: 0.625rem 1rem;
            border: 1.5px solid var(--border);
            border-radius: 9999px;
            font-size: 0.875rem; color: var(--brown);
            background: #fdfaf7; outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
            box-sizing: border-box;
        }
        .filter-input:focus {
            border-color: rgba(255,107,107,0.4);
            box-shadow: 0 0 0 3px rgba(255,107,107,0.08);
        }
        .filter-select {
            width: 100%; padding: 0.625rem 1rem;
            border: 1.5px solid var(--border);
            border-radius: 9999px;
            font-size: 0.875rem; color: var(--brown);
            background: #fdfaf7; outline: none;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%238b7355' stroke-width='2'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            padding-right: 2.5rem;
            transition: border-color 0.2s, box-shadow 0.2s;
            box-sizing: border-box;
        }
        .filter-select:focus {
            border-color: rgba(255,107,107,0.4);
            box-shadow: 0 0 0 3px rgba(255,107,107,0.08);
        }

        /* ── Loading / Empty State ── */
        .loading-state {
            text-align: center; padding: 3rem 0;
            color: var(--brown-light);
        }
        .loading-state .spinner {
            width: 2rem; height: 2rem;
            margin: 0 auto 0.75rem;
            border: 3px solid rgba(180,160,140,0.2);
            border-top-color: var(--coral);
            border-radius: 50%;
            animation: user-spin 0.7s linear infinite;
        }
        @keyframes user-spin { to { transform: rotate(360deg); } }

        .empty-state {
            text-align: center; padding: 4rem 1rem;
            color: var(--brown-light);
        }
        .empty-state .icon {
            font-size: 3.5rem; margin-bottom: 1rem;
        }
        .empty-state p {
            font-size: 1rem; margin-bottom: 1.25rem;
        }

        /* ── Success Banner ── */
        .user-success-banner {
            padding: 1rem 1.25rem;
            border-radius: 1rem;
            font-size: 0.875rem; font-weight: 600;
            background: rgba(152,238,204,0.15);
            color: var(--mint-deep);
            margin-bottom: 1.5rem;
        }

        /* ── Favorites Delete Button ── */
        .fav-card-wrapper {
            position: relative;
        }
        .fav-delete-btn {
            position: absolute; top: 0.75rem; right: 0.75rem;
            z-index: 10;
            width: 2rem; height: 2rem;
            border-radius: 9999px;
            border: none;
            background: #f87171; color: #fff;
            font-size: 0.875rem;
            cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 2px 8px rgba(248,113,113,0.35);
            transition: transform 0.15s, opacity 0.2s;
        }
        .fav-delete-btn:hover { transform: scale(1.1); }
        @media (hover: hover) {
            .fav-delete-btn { opacity: 0; }
            .fav-card-wrapper:hover .fav-delete-btn { opacity: 1; }
        }
        @media (hover: none) {
            .fav-delete-btn { opacity: 1; }
        }

        /* ── Dashboard ── */
        .dashboard-hero {
            text-align: center; padding: 3rem 1rem;
        }
        .dashboard-hero .icon {
            font-size: 4rem; margin-bottom: 1rem;
        }
        .dashboard-hero h1 {
            font-family: 'Be Vietnam Pro', sans-serif;
            font-size: 1.875rem; font-weight: 700;
            color: var(--brown); margin-bottom: 0.5rem;
        }
        .dashboard-hero p {
            font-size: 1.125rem; color: var(--brown-light);
            margin-bottom: 2rem;
        }
        .dashboard-actions {
            display: flex; flex-wrap: wrap; gap: 1rem;
            justify-content: center;
        }

    </style>
</head>
<body class="font-sans antialiased flex flex-col min-h-screen" style="background: #fcf9f5;">
    <?= view('partials.navbar') ?>

    <!-- Page Content -->
    <main class="flex-1 max-w-7xl mx-auto w-full px-4 pt-6 pb-8">
        <?php if (!empty($header)): ?>
            <header class="mb-6 reveal">
                <div class="clay-sm px-6 py-4" style="background: #fff;">
                    <?= $header ?>
                </div>
            </header>
        <?php endif ?>

        <?= $content ?? '' ?>
    </main>

    <?= view('partials.footer') ?>

    <!-- Global Confirm Dialog -->
    <div
        x-data="{
            show: false,
            action: '',
            method: 'DELETE',
            message: '',
            init() {
                window.addEventListener('open-confirm', (e) => {
                    this.action = e.detail.action;
                    this.method = e.detail.method || 'DELETE';
                    this.message = e.detail.message || 'Bạn có chắc chắn?';
                    this.show = true;
                });
            }
        }"
        x-show="show"
        x-cloak
        x-transition:enter="ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 flex items-center justify-center p-4"
        style="background: rgba(74, 55, 40, 0.35); backdrop-filter: blur(4px);"
        @click.self="show = false"
        @keydown.escape.window="show = false"
    >
        <div
            x-show="show"
            x-cloak
            x-transition:enter="ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="w-full max-w-md p-6 clay"
            style="background: #fff;"
            @click.stop=""
        >
            <div class="flex justify-center mb-4">
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-2xl" style="background: rgba(255,107,107,0.1);">
                    ⚠️
                </div>
            </div>
            <h3 class="font-display text-lg font-bold text-center mb-2" style="color: var(--brown);">
                Xác nhận xoá
            </h3>
            <p class="text-sm text-center mb-6 leading-relaxed" style="color: var(--brown-light);" x-text="message"></p>
            <div class="flex gap-3">
                <button type="button" @click="show = false"
                    class="flex-1 px-4 py-2.5 rounded-full font-semibold text-sm transition-all hover:-translate-y-0.5"
                    style="background: rgba(74,55,40,0.06); color: var(--brown);">
                    Huỷ
                </button>
                <form :action="action" method="POST" class="flex-1">
                    <?= csrf_field() ?>
                    <input type="hidden" name="_method" :value="method">
                    <button type="submit"
                        class="w-full px-4 py-2.5 rounded-full font-display font-semibold text-sm text-white transition-all hover:-translate-y-0.5"
                        style="background: var(--coral); box-shadow: 0 4px 14px rgba(255,107,107,0.3);">
                        Xoá
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
