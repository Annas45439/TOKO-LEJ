<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('content') ?>
    <div class="panel">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <div class="panel-title mb-1"><?= esc((string) ($title ?? 'Form Produk')) ?></div>
                <p class="panel-muted mb-0">Lengkapi data produk dengan benar.</p>
            </div>
            <a href="<?= base_url('/produk') ?>" class="btn btn-outline-light">Kembali</a>
        </div>

        <?php $isEdit = !empty($produk); ?>
        <form method="post" action="<?= $isEdit ? base_url('/produk/update/' . $produk['id']) : base_url('/produk/store') ?>" class="row g-3">
            <?= csrf_field() ?>

            <div class="col-12 col-md-6">
                <label class="form-label">Nama Produk</label>
                <input type="text" name="name" class="form-control" required value="<?= esc(old('name', $produk['name'] ?? '')) ?>">
            </div>

            <div class="col-12 col-md-3">
                <label class="form-label">Kategori</label>
                <select name="category_id" class="form-select" required>
                    <option value="">Pilih kategori</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= esc((string) $category['id']) ?>" <?= (old('category_id', $produk['category_id'] ?? '') == $category['id']) ? 'selected' : '' ?>>
                            <?= esc($category['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-12 col-md-3">
                <label class="form-label">Satuan</label>
                <select name="unit_id" class="form-select" required>
                    <option value="">Pilih satuan</option>
                    <?php foreach ($units as $unit): ?>
                        <option value="<?= esc((string) $unit['id']) ?>" <?= (old('unit_id', $produk['unit_id'] ?? '') == $unit['id']) ? 'selected' : '' ?>>
                            <?= esc($unit['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-12 col-md-3">
                <label class="form-label">Harga Jual</label>
                <input type="number" step="0.01" min="0" name="price" class="form-control" required value="<?= esc(old('price', $produk['price'] ?? '0')) ?>">
            </div>

            <div class="col-12 col-md-3">
                <label class="form-label">Harga Beli</label>
                <input type="number" step="0.01" min="0" name="buy_price" class="form-control" required value="<?= esc(old('buy_price', $produk['buy_price'] ?? '0')) ?>">
            </div>

            <div class="col-12 col-md-3">
                <label class="form-label">Stok</label>
                <input type="number" min="0" name="stock" class="form-control" required value="<?= esc(old('stock', $produk['stock'] ?? '0')) ?>">
            </div>

            <div class="col-12 col-md-3">
                <label class="form-label">Stok Minimum</label>
                <input type="number" min="0" name="min_stock" class="form-control" required value="<?= esc(old('min_stock', $produk['min_stock'] ?? '0')) ?>">
            </div>

            <div class="col-12">
                <label class="form-label">Deskripsi</label>
                <textarea name="description" class="form-control" rows="4" placeholder="Opsional"><?= esc(old('description', $produk['description'] ?? '')) ?></textarea>
            </div>

            <div class="col-12 d-flex gap-2">
                <button type="submit" class="btn btn-info"><?= $isEdit ? 'Update Produk' : 'Simpan Produk' ?></button>
                <a href="<?= base_url('/produk') ?>" class="btn btn-outline-light">Batal</a>
            </div>
        </form>
    </div>
<?= $this->endSection() ?>