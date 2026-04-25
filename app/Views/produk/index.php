<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('content') ?>
    <div class="panel">
        <div class="d-flex flex-column flex-lg-row justify-content-between gap-3 mb-3">
            <div>
                <div class="panel-title mb-1">Manajemen Produk</div>
                <p class="panel-muted mb-0">Kelola data produk, harga, dan status stok minimum.</p>
            </div>
            <?php if (($level ?? '') === 'admin'): ?>
                <a href="<?= base_url('/produk/create') ?>" class="btn btn-info">+ Tambah Produk</a>
            <?php endif; ?>
        </div>

        <form method="get" action="<?= base_url('/produk') ?>" class="row g-2 mb-3">
            <div class="col-12 col-md-6">
                <input
                    type="text"
                    name="search"
                    value="<?= esc((string) ($search ?? '')) ?>"
                    class="form-control"
                    placeholder="Cari nama produk atau kategori...">
            </div>
            <div class="col-8 col-md-4">
                <select name="category_id" class="form-select">
                    <option value="0">Semua Kategori</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= esc((string) $category['id']) ?>" <?= ((int) ($categoryId ?? 0) === (int) $category['id']) ? 'selected' : '' ?>>
                            <?= esc($category['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-4 col-md-2 d-grid">
                <button type="submit" class="btn btn-outline-light">Filter</button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-dark table-hover align-middle" style="--bs-table-bg: transparent; --bs-table-striped-bg: rgba(255,255,255,0.03); --bs-table-hover-bg: rgba(255,255,255,0.06);">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Produk</th>
                        <th>Kategori</th>
                        <th>Harga Jual</th>
                        <th>Harga Beli</th>
                        <th>Stok</th>
                        <th>Stok Min</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($produkList)): ?>
                        <tr>
                            <td colspan="9" class="text-center text-secondary py-4">Belum ada data produk.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($produkList as $index => $produk): ?>
                            <?php $isLow = (int) $produk['stock'] <= (int) $produk['min_stock']; ?>
                            <tr>
                                <td><?= $index + 1 ?></td>
                                <td>
                                    <div class="fw-semibold"><?= esc($produk['name']) ?></div>
                                    <small class="text-secondary"><?= esc((string) ($produk['unit_name'] ?? '-')) ?></small>
                                </td>
                                <td><?= esc((string) ($produk['category_name'] ?? '-')) ?></td>
                                <td>Rp <?= number_format((float) $produk['price'], 0, ',', '.') ?></td>
                                <td>Rp <?= number_format((float) $produk['buy_price'], 0, ',', '.') ?></td>
                                <td><?= esc((string) $produk['stock']) ?></td>
                                <td><?= esc((string) $produk['min_stock']) ?></td>
                                <td>
                                    <?php if ($isLow): ?>
                                        <span class="badge text-bg-warning">HAMPIR HABIS</span>
                                    <?php else: ?>
                                        <span class="badge text-bg-success">AMAN</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (($level ?? '') === 'admin'): ?>
                                        <div class="d-flex gap-1">
                                            <a href="<?= base_url('/produk/edit/' . $produk['id']) ?>" class="btn btn-sm btn-outline-info">Edit</a>
                                            <form method="post" action="<?= base_url('/produk/delete/' . $produk['id']) ?>" onsubmit="return confirm('Hapus produk ini?');">
                                                <?= csrf_field() ?>
                                                <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                                            </form>
                                        </div>
                                    <?php else: ?>
                                        <small class="text-secondary">Read Only</small>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
<?= $this->endSection() ?>