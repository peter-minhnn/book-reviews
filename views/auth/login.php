<?php ob_start() ?>

<div class="mb-6 text-center">
    <h2 class="text-2xl font-display font-bold" style="color: var(--brown);">Đăng nhập</h2>
    <p class="text-sm mt-1" style="color: var(--brown-light);">Chào mừng bạn trở lại!</p>
</div>

<?php if ($error = session('errors')['email'][0] ?? ''): ?>
    <div class="mb-4 p-3 rounded-xl text-sm" style="background: rgba(255,107,107,0.1); color: #f87171;">
        <?= e($error) ?>
    </div>
<?php endif ?>

<form method="POST" action="<?= route('login') ?>">
    <?= csrf_field() ?>

    <div class="mb-4">
        <label for="email" class="block text-sm font-semibold mb-1.5" style="color: var(--brown);">Email</label>
        <input id="email" type="email" name="email" value="<?= e(old('email')) ?>" required autofocus
               placeholder="Nhập địa chỉ email"
               class="w-full px-4 py-2.5 rounded-full text-sm outline-none transition-shadow focus:shadow-md"
               style="border: 1.5px solid rgba(180,160,140,0.2); background: #fdfaf7; color: var(--brown);">
    </div>

    <div class="mb-4">
        <label for="password" class="block text-sm font-semibold mb-1.5" style="color: var(--brown);">Mật khẩu</label>
        <input id="password" type="password" name="password" required
               placeholder="Nhập mật khẩu"
               class="w-full px-4 py-2.5 rounded-full text-sm outline-none transition-shadow focus:shadow-md"
               style="border: 1.5px solid rgba(180,160,140,0.2); background: #fdfaf7; color: var(--brown);">
    </div>

    <div class="mb-4 flex items-center gap-2">
        <input type="checkbox" id="remember" name="remember" class="rounded" style="accent-color: var(--coral);">
        <label for="remember" class="text-sm" style="color: var(--brown-light);">Ghi nhớ đăng nhập</label>
    </div>

    <button type="submit" class="w-full px-5 py-2.5 rounded-full font-display font-semibold text-sm text-white transition-all hover:-translate-y-0.5"
            style="background: var(--coral); box-shadow: 0 4px 14px rgba(255,107,107,0.25);">
        Đăng nhập
    </button>
</form>

<div class="mt-6 text-center text-sm">
    <p style="color: var(--brown-light);">
        <a href="<?= route('register') ?>" style="color: var(--coral); font-weight: 600;">Chưa có tài khoản? Đăng ký</a>
    </p>
</div>

<?php $content = ob_get_clean() ?>
<?php require __DIR__ . '/../layouts/guest.php' ?>
