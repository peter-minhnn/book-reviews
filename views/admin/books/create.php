<?php $title = 'Thêm sách mới' ?>
<?php $breadcrumbs = [
    ['label' => 'Quản lý sách', 'url' => route('admin.books.index')],
    ['label' => 'Thêm sách mới', 'url' => '#'],
] ?>
<?php ob_start() ?>

<div class="admin-form-page">
    <div class="admin-form-shell wide">
        <h1 class="admin-page-title" style="margin-bottom:1.5rem">Thêm sách mới</h1>

        <form method="POST" action="<?= route('admin.books.store') ?>" enctype="multipart/form-data" class="admin-form-card">
        <?= csrf_field() ?>

        <div class="admin-form-grid cols-2" style="margin-bottom:1rem">
            <div>
                <label class="admin-form-label">Tiêu đề <span class="required">*</span></label>
                <input type="text" name="title" value="<?= e(old('title')) ?>" required
                       placeholder="Nhập tiêu đề sách" class="admin-input">
                <?php if ($err = session('errors')['title'][0] ?? ''): ?><p class="admin-form-error"><?= e($err) ?></p><?php endif ?>
            </div>
            <div>
                <label class="admin-form-label">Tác giả <span class="required">*</span></label>
                <input type="text" name="author" value="<?= e(old('author')) ?>" required
                       placeholder="Nhập tên tác giả" class="admin-input">
                <?php if ($err = session('errors')['author'][0] ?? ''): ?><p class="admin-form-error"><?= e($err) ?></p><?php endif ?>
            </div>
        </div>

        <div class="admin-form-grid cols-2" style="margin-bottom:1rem">
            <div>
                <label class="admin-form-label">Danh mục <span class="required">*</span></label>
                <div class="admin-select-wrap">
                    <select name="category_id" required>
                        <option value="">-- Chọn danh mục --</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>" <?= old('category_id') == $cat['id'] ? 'selected' : '' ?>><?= e($cat['name']) ?></option>
                        <?php endforeach ?>
                    </select>
                </div>
                <?php if ($err = session('errors')['category_id'][0] ?? ''): ?><p class="admin-form-error"><?= e($err) ?></p><?php endif ?>
            </div>
            <div>
                <label class="admin-form-label">Năm xuất bản</label>
                <input type="number" name="published_year" value="<?= e(old('published_year')) ?>"
                       placeholder="Ví dụ: 2024" min="1000" max="<?= date('Y') ?>" class="admin-input">
            </div>
        </div>

        <div style="margin-bottom:1rem">
            <label class="admin-form-label">Mô tả</label>
            <textarea name="description" rows="4" maxlength="5000"
                      placeholder="Nhập mô tả về cuốn sách..." class="admin-textarea"><?= e(old('description')) ?></textarea>
            <p class="admin-form-help">Tối đa 5000 ký tự.</p>
        </div>

        <div style="margin-bottom:1rem">
            <label class="admin-form-label">Ảnh bìa</label>
            <div class="admin-file-upload" id="cover-upload-zone">
                <!-- Default: upload prompt -->
                <div id="cover-upload-prompt" style="display:flex;flex-direction:column;align-items:center;gap:0.5rem;position:relative">
                    <svg style="width:2.5rem;height:2.5rem;color:#d1d5db" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <p style="font-size:0.875rem;color:#6b7280"><span style="font-weight:600;color:#2563eb">Nhấp để chọn ảnh</span> hoặc kéo thả</p>
                    <p class="admin-form-help">JPEG, PNG hoặc WebP. Tối đa 2MB.</p>
                    <input type="file" name="cover_image" id="cover-file-input"
                           accept="image/jpeg,image/jpg,image/png,image/webp"
                           onchange="handleCoverSelect(this)"
                           style="position:absolute;inset:0;opacity:0;cursor:pointer">
                </div>
                <!-- Preview (hidden by default) -->
                <div id="cover-preview" style="display:none;flex-direction:column;align-items:center;gap:0.75rem">
                    <img id="cover-preview-img" src="" alt="Preview"
                         style="max-width:180px;max-height:240px;object-fit:contain;border-radius:0.5rem;border:1px solid #e5e7eb;box-shadow:0 1px 3px rgba(0,0,0,0.08)">
                    <div style="text-align:center">
                        <p id="cover-preview-name" style="font-size:0.875rem;font-weight:600;color:#374151;word-break:break-all"></p>
                        <p id="cover-preview-size" style="font-size:0.75rem;color:#9ca3af;margin-top:0.125rem"></p>
                    </div>
                    <button type="button" onclick="clearCoverImage()"
                            style="display:inline-flex;align-items:center;gap:0.375rem;padding:0.375rem 0.875rem;font-size:0.75rem;font-weight:600;color:#dc2626;background:#fef2f2;border:1px solid #fecaca;border-radius:0.5rem;cursor:pointer;transition:all 0.15s"
                            onmouseover="this.style.background='#fee2e2'" onmouseout="this.style.background='#fef2f2'">
                        <svg style="width:0.875rem;height:0.875rem" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        Chọn ảnh khác
                    </button>
                </div>
            </div>
            <?php if ($err = session('errors')['cover_image'][0] ?? ''): ?><p class="admin-form-error"><?= e($err) ?></p><?php endif ?>
        </div>

        <script>
        function formatFileSize(bytes) {
            if (bytes < 1024) return bytes + ' B';
            if (bytes < 1048576) return (bytes / 1024).toFixed(1) + ' KB';
            return (bytes / 1048576).toFixed(1) + ' MB';
        }

        function handleCoverSelect(input) {
            const file = input.files[0];
            const zone = document.getElementById('cover-upload-zone');
            const prompt = document.getElementById('cover-upload-prompt');
            const preview = document.getElementById('cover-preview');
            const img = document.getElementById('cover-preview-img');
            const nameEl = document.getElementById('cover-preview-name');
            const sizeEl = document.getElementById('cover-preview-size');

            if (file) {
                // Show preview
                img.src = URL.createObjectURL(file);
                nameEl.textContent = file.name;
                sizeEl.textContent = formatFileSize(file.size);
                prompt.style.display = 'none';
                preview.style.display = 'flex';
                zone.classList.add('has-file');
            } else {
                clearCoverImage();
            }
        }

        function clearCoverImage() {
            const zone = document.getElementById('cover-upload-zone');
            const prompt = document.getElementById('cover-upload-prompt');
            const preview = document.getElementById('cover-preview');
            const input = document.getElementById('cover-file-input');
            const img = document.getElementById('cover-preview-img');

            input.value = '';
            img.src = '';
            prompt.style.display = 'flex';
            preview.style.display = 'none';
            zone.classList.remove('has-file');
        }

        // Drag & drop support
        (function() {
            const zone = document.getElementById('cover-upload-zone');
            const input = document.getElementById('cover-file-input');

            zone.addEventListener('dragover', function(e) {
                e.preventDefault();
                zone.style.borderColor = '#93c5fd';
                zone.style.backgroundColor = '#f8fafc';
            });
            zone.addEventListener('dragleave', function(e) {
                e.preventDefault();
                zone.style.borderColor = '';
                zone.style.backgroundColor = '';
            });
            zone.addEventListener('drop', function(e) {
                e.preventDefault();
                zone.style.borderColor = '';
                zone.style.backgroundColor = '';
                if (e.dataTransfer.files.length > 0) {
                    input.files = e.dataTransfer.files;
                    handleCoverSelect(input);
                }
            });
        })();
        </script>

        <div class="admin-form-actions">
            <button type="submit" class="admin-btn-primary">Tạo sách</button>
            <a href="<?= route('admin.books.index') ?>" class="admin-btn-secondary">Huỷ</a>
        </div>
        </form>
    </div>
</div>

<?php $content = ob_get_clean() ?>
<?php require __DIR__ . '/../../layouts/admin.php' ?>
