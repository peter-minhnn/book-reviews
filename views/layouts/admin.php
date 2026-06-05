<!DOCTYPE html>
<html lang="vi" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?= csrf_token() ?>">
    <title>Admin — <?= e($title ?? 'Bảng điều khiển') ?></title>
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=be-vietnam-pro:400,500,600,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="<?= vite_asset('resources/css/app.css') ?>">
    <link rel="stylesheet" href="/css/ui.css">
    <script defer src="<?= vite_asset('resources/js/app.js') ?>"></script>
</head>
<body class="h-full bg-gray-100 text-gray-900">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside id="sidebar" class="w-72 bg-linear-to-b from-gray-900 to-gray-800 text-white flex flex-col shadow-xl shrink-0 transition-all duration-300 ease-in-out overflow-hidden">
            <div id="sidebar-brand-header" title="Admin Panel" class="h-16 px-5 text-xl font-bold border-b border-gray-700/60 flex items-center gap-3 shrink-0">
                <svg class="w-7 h-7 text-blue-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <span id="sidebar-brand" class="whitespace-nowrap transition-opacity duration-200">Admin Panel</span>
            </div>
            <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto overflow-x-hidden">
                <a href="<?= route('admin.dashboard') ?>" title="Bảng điều khiển"
                   class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-gray-700/50 transition-colors <?= request()->routeIs('/admin') && !str_contains($_SERVER['REQUEST_URI'] ?? '', '/admin/') ? 'bg-gray-700/70 text-white font-medium' : 'text-gray-300' ?>">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                    <span class="sidebar-text whitespace-nowrap transition-opacity duration-200">Bảng điều khiển</span>
                </a>
                <a href="<?= route('admin.books.index') ?>" title="Quản lý sách"
                   class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-gray-700/50 transition-colors <?= str_contains($_SERVER['REQUEST_URI'] ?? '', '/admin/books') ? 'bg-gray-700/70 text-white font-medium' : 'text-gray-300' ?>">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    <span class="sidebar-text whitespace-nowrap transition-opacity duration-200">Quản lý sách</span>
                </a>
                <a href="<?= route('admin.categories.index') ?>" title="Quản lý danh mục"
                   class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-gray-700/50 transition-colors <?= str_contains($_SERVER['REQUEST_URI'] ?? '', '/admin/categories') ? 'bg-gray-700/70 text-white font-medium' : 'text-gray-300' ?>">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                    <span class="sidebar-text whitespace-nowrap transition-opacity duration-200">Quản lý danh mục</span>
                </a>
                <a href="<?= route('admin.reviews.index') ?>" title="Quản lý đánh giá"
                   class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-gray-700/50 transition-colors <?= str_contains($_SERVER['REQUEST_URI'] ?? '', '/admin/reviews') ? 'bg-gray-700/70 text-white font-medium' : 'text-gray-300' ?>">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                    <span class="sidebar-text whitespace-nowrap transition-opacity duration-200">Quản lý đánh giá</span>
                </a>
                <a href="<?= route('admin.users.index') ?>" title="Quản lý người dùng"
                   class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-gray-700/50 transition-colors <?= str_contains($_SERVER['REQUEST_URI'] ?? '', '/admin/users') ? 'bg-gray-700/70 text-white font-medium' : 'text-gray-300' ?>">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    <span class="sidebar-text whitespace-nowrap transition-opacity duration-200">Quản lý người dùng</span>
                </a>
            </nav>
            <div class="px-3 py-3 border-t border-gray-700/50 space-y-1 shrink-0">
                <a href="<?= route('home') ?>" title="Về trang chủ"
                   class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-gray-700/50 text-gray-400 hover:text-gray-200 transition-colors">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    <span class="sidebar-text whitespace-nowrap transition-opacity duration-200">Về trang chủ</span>
                </a>
                <form method="POST" action="<?= route('logout') ?>" class="sidebar-link">
                    <?= csrf_field() ?>
                    <button type="submit" title="Đăng xuất"
                            class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-red-500/20 text-red-400 hover:text-red-300 transition-colors">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        <span class="sidebar-text whitespace-nowrap transition-opacity duration-200">Đăng xuất</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col min-w-0 min-h-0">
            <!-- Top Header -->
            <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6 shrink-0 shadow-sm">
                <button id="sidebar-toggle"
                        class="flex items-center justify-center w-9 h-9 rounded-lg hover:bg-gray-100 transition-colors text-gray-500 hover:text-gray-700"
                        title="Thu gọn menu">
                    <svg id="toggle-icon-collapse" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.75 6.75h16.5M3.75 12h16.5M3.75 17.25h16.5"/></svg>
                    <svg id="toggle-icon-expand" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.75 5.25h16.5M3.75 9.75h12M3.75 14.25h16.5M3.75 18.75h12"/></svg>
                </button>

                <div class="relative">
                    <button id="user-menu-btn"
                            class="flex items-center gap-3 hover:bg-gray-50 rounded-lg px-3 py-2 transition-colors"
                            onclick="toggleUserMenu()">
                        <span class="text-sm font-medium text-gray-700 hidden sm:block"><?= e(auth()['name'] ?? '') ?></span>
                        <div class="w-9 h-9 rounded-full bg-linear-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold text-sm shadow-md">
                            <?= e(mb_strtoupper(mb_substr(auth()['name'] ?? 'U', 0, 1))) ?>
                        </div>
                        <svg class="w-4 h-4 text-gray-400 transition-transform" id="dropdown-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div id="user-dropdown" class="absolute right-0 top-full mt-2 w-64 bg-white rounded-xl shadow-xl border border-gray-200 overflow-hidden z-50 hidden">
                        <div class="px-4 py-4 border-b border-gray-100">
                            <div class="flex items-center gap-3">
                                <div class="w-11 h-11 rounded-full bg-linear-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold text-lg shadow-md shrink-0">
                                    <?= e(mb_strtoupper(mb_substr(auth()['name'] ?? 'U', 0, 1))) ?>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-semibold text-gray-900 truncate"><?= e(auth()['name'] ?? '') ?></p>
                                    <p class="text-xs text-gray-500 truncate"><?= e(auth()['email'] ?? '') ?></p>
                                </div>
                            </div>
                            <span class="inline-block mt-2 px-2 py-0.5 text-xs font-medium rounded-full <?= (auth()['role'] ?? '') === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-700' ?>">
                                <?= (auth()['role'] ?? '') === 'admin' ? 'Quản trị viên' : 'Người dùng' ?>
                            </span>
                        </div>
                        <div class="p-2">
                            <form method="POST" action="<?= route('logout') ?>">
                                <?= csrf_field() ?>
                                <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 text-sm text-red-600 hover:bg-red-50 rounded-lg transition-colors font-medium">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                    Đăng xuất
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Breadcrumbs -->
            <?php if (!empty($breadcrumbs)): ?>
                <div class="admin-breadcrumbs">
                    <a href="<?= route('admin.dashboard') ?>">Admin</a>
                    <?php foreach ($breadcrumbs as $i => $crumb): ?>
                        <span class="sep">/</span>
                        <?php if ($i < count($breadcrumbs) - 1): ?>
                            <a href="<?= e($crumb['url']) ?>"><?= e($crumb['label']) ?></a>
                        <?php else: ?>
                            <span class="current"><?= e($crumb['label']) ?></span>
                        <?php endif ?>
                    <?php endforeach ?>
                </div>
            <?php endif ?>

            <!-- Page Content -->
            <main class="flex-1 flex flex-col min-h-0 overflow-hidden p-6 bg-linear-to-br from-gray-50 to-gray-100">
                <?= $content ?? '' ?>
            </main>
        </div>
    </div>

    <!-- Toast Container -->
    <div id="toast-container" class="fixed top-4 right-4 z-9999 flex flex-col gap-3 max-w-sm w-full pointer-events-none">
        <?php if (session('success')): ?>
            <div class="toast-item toast-enter pointer-events-auto bg-white rounded-xl shadow-2xl border border-emerald-200 overflow-hidden" role="alert">
                <div class="flex items-start gap-3 px-5 py-4">
                    <div class="shrink-0 w-9 h-9 bg-emerald-100 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900">Thành công</p>
                        <p class="text-sm text-gray-600 mt-0.5"><?= e(session('success')) ?></p>
                    </div>
                    <button onclick="dismissToast(this.closest('.toast-item'))" class="shrink-0 text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <div class="h-1 bg-emerald-100"><div class="toast-progress h-full bg-emerald-500"></div></div>
            </div>
        <?php endif ?>
        <?php if (session('error')): ?>
            <div class="toast-item toast-enter pointer-events-auto bg-white rounded-xl shadow-2xl border border-rose-200 overflow-hidden" role="alert">
                <div class="flex items-start gap-3 px-5 py-4">
                    <div class="shrink-0 w-9 h-9 bg-rose-100 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900">Lỗi</p>
                        <p class="text-sm text-gray-600 mt-0.5"><?= e(session('error')) ?></p>
                    </div>
                    <button onclick="dismissToast(this.closest('.toast-item'))" class="shrink-0 text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <div class="h-1 bg-rose-100"><div class="toast-progress h-full bg-rose-500"></div></div>
            </div>
        <?php endif ?>
    </div>

    <!-- Global Confirm Dialog -->
    <div x-data="{ show: false, action: '', method: 'DELETE', message: '', init() { window.addEventListener('open-confirm', (e) => { this.action = e.detail.action; this.method = e.detail.method || 'DELETE'; this.message = e.detail.message || 'Bạn có chắc chắn?'; this.show = true; }); } }"
         x-show="show" x-cloak @click.self="show = false" @keydown.escape.window="show = false"
         class="fixed inset-0 z-9999 flex items-center justify-center p-4" style="background: rgba(74, 55, 40, 0.35); backdrop-filter: blur(4px);">
        <div x-show="show" x-cloak @click.stop=""
             class="w-full max-w-md p-6 clay" style="background: #fff;">
            <div class="flex justify-center mb-4">
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-2xl" style="background: rgba(255,107,107,0.1);">⚠️</div>
            </div>
            <h3 class="font-display text-lg font-bold text-center mb-2" style="color: var(--brown);">Xác nhận xoá</h3>
            <p class="text-sm text-center mb-6 leading-relaxed" style="color: var(--brown-light);" x-text="message"></p>
            <div class="flex gap-3">
                <button type="button" @click="show = false"
                    class="flex-1 px-4 py-2.5 rounded-full font-semibold text-sm transition-all"
                    style="background: rgba(74,55,40,0.06); color: var(--brown);">Huỷ</button>
                <form :action="action" method="POST" class="flex-1">
                    <?= csrf_field() ?>
                    <input type="hidden" name="_method" :value="method">
                    <button type="submit"
                        class="w-full px-4 py-2.5 rounded-full font-display font-semibold text-sm text-white transition-all"
                        style="background: var(--coral); box-shadow: 0 4px 14px rgba(255,107,107,0.3);">Xoá</button>
                </form>
            </div>
        </div>
    </div>

    <script>
    function dismissToast(el) { el.classList.remove('toast-enter'); el.classList.add('toast-exit'); setTimeout(() => el.remove(), 300); }
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.toast-item').forEach(function (el) { setTimeout(function () { dismissToast(el); }, 4000); });
    });
    function toggleUserMenu() {
        var dropdown = document.getElementById('user-dropdown');
        var chevron = document.getElementById('dropdown-chevron');
        dropdown.classList.toggle('hidden');
        chevron.classList.toggle('rotate-180');
    }
    document.addEventListener('click', function (e) {
        var btn = document.getElementById('user-menu-btn');
        var dropdown = document.getElementById('user-dropdown');
        if (!btn.contains(e.target) && !dropdown.contains(e.target)) {
            dropdown.classList.add('hidden');
            document.getElementById('dropdown-chevron').classList.remove('rotate-180');
        }
    });
    (function() {
        var SIDEBAR_COLLAPSED_KEY = 'admin_sidebar_collapsed';
        var sidebar = document.getElementById('sidebar');
        var toggleBtn = document.getElementById('sidebar-toggle');
        var toggleIconCollapse = document.getElementById('toggle-icon-collapse');
        var toggleIconExpand = document.getElementById('toggle-icon-expand');
        var sidebarTexts = document.querySelectorAll('.sidebar-text');
        var sidebarBrand = document.getElementById('sidebar-brand');
        var sidebarLinks = document.querySelectorAll('.sidebar-link');
        var sidebarLinkButtons = document.querySelectorAll('.sidebar-link > button');
        var sidebarBrandHeader = document.getElementById('sidebar-brand-header');

        function setCollapsed(collapsed) {
            if (collapsed) {
                sidebar.classList.add('w-20'); sidebar.classList.remove('w-72');
                sidebarTexts.forEach(function(el) { el.classList.add('hidden'); });
                sidebarBrand.classList.add('hidden');
                sidebarLinks.forEach(function(el) { el.classList.add('justify-center'); el.classList.remove('gap-3'); });
                sidebarLinkButtons.forEach(function(el) { el.classList.add('justify-center'); el.classList.remove('gap-3'); });
                sidebarBrandHeader.classList.add('justify-center'); sidebarBrandHeader.classList.remove('px-5');
                toggleIconCollapse.classList.add('hidden'); toggleIconExpand.classList.remove('hidden');
                toggleBtn.setAttribute('title', 'Mở rộng menu');
            } else {
                sidebar.classList.add('w-72'); sidebar.classList.remove('w-20');
                sidebarTexts.forEach(function(el) { el.classList.remove('hidden'); });
                sidebarBrand.classList.remove('hidden');
                sidebarLinks.forEach(function(el) { el.classList.remove('justify-center'); el.classList.add('gap-3'); });
                sidebarLinkButtons.forEach(function(el) { el.classList.remove('justify-center'); el.classList.add('gap-3'); });
                sidebarBrandHeader.classList.remove('justify-center'); sidebarBrandHeader.classList.add('px-5');
                toggleIconCollapse.classList.remove('hidden'); toggleIconExpand.classList.add('hidden');
                toggleBtn.setAttribute('title', 'Thu gọn menu');
            }
        }

        var saved = localStorage.getItem(SIDEBAR_COLLAPSED_KEY);
        if (saved === 'true') setCollapsed(true);

        toggleBtn.addEventListener('click', function () {
            var isCollapsed = sidebar.classList.contains('w-20');
            var newState = !isCollapsed;
            setCollapsed(newState);
            localStorage.setItem(SIDEBAR_COLLAPSED_KEY, newState);
        });

        // Tooltips
        var tooltip = document.createElement('div');
        tooltip.className = 'sidebar-tooltip';
        document.body.appendChild(tooltip);

        function getTitle(el) { var target = el.closest('[title]'); return target ? target.getAttribute('title') : ''; }
        function showTooltip(el) {
            if (!document.getElementById('sidebar').classList.contains('w-20')) return;
            var text = getTitle(el); if (!text) return;
            tooltip.textContent = text;
            var linkEl = el.closest('.sidebar-link');
            var rect = (linkEl || el).getBoundingClientRect();
            tooltip.style.left = (rect.right + 10) + 'px';
            tooltip.style.top = (rect.top + rect.height / 2) + 'px';
            tooltip.style.transform = 'translateY(-50%)';
            tooltip.classList.add('show');
        }
        function hideTooltip() { tooltip.classList.remove('show'); }

        document.querySelectorAll('.sidebar-link').forEach(function(link) {
            link.addEventListener('mouseenter', function() { showTooltip(link); });
            link.addEventListener('mouseleave', hideTooltip);
        });
        var brandHeader = document.getElementById('sidebar-brand-header');
        if (brandHeader) {
            brandHeader.addEventListener('mouseenter', function() { showTooltip(brandHeader); });
            brandHeader.addEventListener('mouseleave', hideTooltip);
        }
    })();
    if ('scrollRestoration' in history) { history.scrollRestoration = 'manual'; }
    </script>
</body>
</html>
