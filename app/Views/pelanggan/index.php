<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('content') ?>
    <div class="row g-3">
        <div class="col-12 col-xl-4">
            <div class="panel">
                <div class="panel-title"><?= empty($editRow) ? 'Tambah Pelanggan' : 'Edit Pelanggan' ?></div>
                <p class="panel-muted mb-3">Kelola data master pelanggan untuk transaksi POS.</p>

                <?php if (! $hasTable): ?>
                    <div class="alert alert-danger mb-0">Tabel tb_customers belum tersedia pada database.</div>
                <?php elseif (! $isAdmin): ?>
                    <div class="alert alert-warning mb-0">Mode Read Only untuk kasir.</div>
                <?php else: ?>
                    <form method="post" action="<?= empty($editRow) ? base_url('/pelanggan/store') : base_url('/pelanggan/update/' . (int) $editRow['id']) ?>" class="row g-2">
                        <?= csrf_field() ?>

                        <?php if (in_array('name', $fields, true)): ?>
                            <div class="col-12">
                                <label class="form-label">Nama</label>
                                <input type="text" name="name" class="form-control" required value="<?= esc(old('name', $editRow['name'] ?? '')) ?>">
                            </div>
                        <?php endif; ?>

                        <?php if (in_array('phone', $fields, true)): ?>
                            <div class="col-12">
                                <label class="form-label">Telepon</label>
                                <input type="text" name="phone" class="form-control" value="<?= esc(old('phone', $editRow['phone'] ?? '')) ?>">
                            </div>
                        <?php endif; ?>

                        <?php if (in_array('email', $fields, true)): ?>
                            <div class="col-12">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" value="<?= esc(old('email', $editRow['email'] ?? '')) ?>">
                            </div>
                        <?php endif; ?>

                        <?php if (in_array('address', $fields, true)): ?>
                            <div class="col-12">
                                <label class="form-label">Alamat</label>
                                <textarea name="address" rows="3" class="form-control"><?= esc(old('address', $editRow['address'] ?? '')) ?></textarea>
                            </div>
                        <?php endif; ?>

                        <?php if (in_array('notes', $fields, true)): ?>
                            <div class="col-12">
                                <label class="form-label">Catatan</label>
                                <textarea name="notes" rows="2" class="form-control"><?= esc(old('notes', $editRow['notes'] ?? '')) ?></textarea>
                            </div>
                        <?php endif; ?>

                        <div class="col-12 d-flex gap-2 mt-2">
                            <button type="submit" class="btn btn-info"><?= empty($editRow) ? 'Simpan' : 'Update' ?></button>
                            <?php if (! empty($editRow)): ?>
                                <a href="<?= base_url('/pelanggan') ?>" class="btn btn-outline-light">Batal Edit</a>
                            <?php endif; ?>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
        </div>

        <div class="col-12 col-xl-8">
            <div class="panel">
                <div class="d-flex flex-column flex-lg-row justify-content-between gap-2 mb-3">
                    <div>
                        <div class="panel-title mb-1">Daftar Pelanggan</div>
                        <p class="panel-muted mb-0">Gunakan pencarian untuk mempercepat input transaksi.</p>
                    </div>

                    <?php if ($hasTable): ?>
                        <form method="get" action="<?= base_url('/pelanggan') ?>" class="d-flex gap-2">
                            <input type="text" name="search" value="<?= esc((string) $search) ?>" class="form-control" placeholder="Cari nama pelanggan...">
                            <button type="submit" class="btn btn-outline-light">Cari</button>
                        </form>
                    <?php endif; ?>
                </div>

                <div class="table-responsive">
                    <?php
                    $columnCount = 3;
                    $columnCount += in_array('phone', $fields, true) ? 1 : 0;
                    $columnCount += in_array('email', $fields, true) ? 1 : 0;
                    $columnCount += in_array('address', $fields, true) ? 1 : 0;
                    ?>
                    <table class="table table-dark table-hover align-middle" style="--bs-table-bg: transparent; --bs-table-hover-bg: rgba(255,255,255,0.06);">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <?php if (in_array('phone', $fields, true)): ?><th>Telepon</th><?php endif; ?>
                                <?php if (in_array('email', $fields, true)): ?><th>Email</th><?php endif; ?>
                                <?php if (in_array('address', $fields, true)): ?><th>Alamat</th><?php endif; ?>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (! $hasTable): ?>
                                <tr>
                                    <td colspan="<?= $columnCount ?>" class="text-center text-danger py-4">Tabel tb_customers belum tersedia.</td>
                                </tr>
                            <?php elseif (empty($rows)): ?>
                                <tr>
                                    <td colspan="<?= $columnCount ?>" class="text-center text-secondary py-4">Belum ada data pelanggan.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($rows as $index => $row): ?>
                                    <tr>
                                        <td><?= $index + 1 ?></td>
                                        <td><?= esc((string) ($row['name'] ?? '-')) ?></td>
                                        <?php if (in_array('phone', $fields, true)): ?><td><?= esc((string) ($row['phone'] ?? '-')) ?></td><?php endif; ?>
                                        <?php if (in_array('email', $fields, true)): ?><td><?= esc((string) ($row['email'] ?? '-')) ?></td><?php endif; ?>
                                        <?php if (in_array('address', $fields, true)): ?><td><?= esc((string) ($row['address'] ?? '-')) ?></td><?php endif; ?>
                                        <td>
                                            <?php if ($isAdmin): ?>
                                                <div class="d-flex gap-1">
                                                    <a href="<?= base_url('/pelanggan?edit=' . (int) $row['id']) ?>" class="btn btn-sm btn-outline-info">Edit</a>
                                                    <form method="post" action="<?= base_url('/pelanggan/delete/' . (int) $row['id']) ?>" onsubmit="return confirm('Hapus pelanggan ini?');">
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
        </div>
    </div>
<?= $this->endSection() ?>
