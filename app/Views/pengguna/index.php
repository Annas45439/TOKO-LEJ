<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('content') ?>
    <div class="row g-3">
        <div class="col-12 col-xl-4">
            <div class="panel">
                <div class="panel-title"><?= empty($editRow) ? 'Tambah Pengguna' : 'Edit Pengguna' ?></div>
                <p class="panel-muted mb-3">Kelola akun admin dan kasir untuk akses sistem.</p>

                <?php if (! $hasTable): ?>
                    <div class="alert alert-danger mb-0">Tabel tb_users belum tersedia pada database.</div>
                <?php else: ?>
                    <form method="post" action="<?= empty($editRow) ? base_url('/pengguna/store') : base_url('/pengguna/update/' . (int) $editRow['id']) ?>" class="row g-2">
                        <?= csrf_field() ?>

                        <div class="col-12">
                            <label class="form-label">Username</label>
                            <input type="text" name="username" class="form-control" required value="<?= esc(old('username', $editRow['username'] ?? '')) ?>">
                        </div>

                        <div class="col-12">
                            <label class="form-label"><?= empty($editRow) ? 'Password' : 'Password Baru (opsional)' ?></label>
                            <input type="password" name="password" class="form-control" <?= empty($editRow) ? 'required' : '' ?> placeholder="Minimal 6 karakter">
                        </div>

                        <div class="col-12">
                            <label class="form-label">Level</label>
                            <?php $levelValue = old('level', $editRow['level'] ?? 'kasir'); ?>
                            <select name="level" class="form-select" required>
                                <option value="admin" <?= $levelValue === 'admin' ? 'selected' : '' ?>>Admin</option>
                                <option value="kasir" <?= $levelValue === 'kasir' ? 'selected' : '' ?>>Kasir</option>
                            </select>
                        </div>

                        <div class="col-12 d-flex gap-2 mt-2">
                            <button type="submit" class="btn btn-info"><?= empty($editRow) ? 'Simpan' : 'Update' ?></button>
                            <?php if (! empty($editRow)): ?>
                                <a href="<?= base_url('/pengguna') ?>" class="btn btn-outline-light">Batal Edit</a>
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
                        <div class="panel-title mb-1">Daftar Pengguna</div>
                        <p class="panel-muted mb-0">Pastikan setiap akun memiliki level akses yang tepat.</p>
                    </div>

                    <?php if ($hasTable): ?>
                        <form method="get" action="<?= base_url('/pengguna') ?>" class="d-flex gap-2">
                            <input type="text" name="search" value="<?= esc((string) $search) ?>" class="form-control" placeholder="Cari username...">
                            <button type="submit" class="btn btn-outline-light">Cari</button>
                        </form>
                    <?php endif; ?>
                </div>

                <div class="table-responsive">
                    <table class="table table-dark table-hover align-middle" style="--bs-table-bg: transparent; --bs-table-hover-bg: rgba(255,255,255,0.06);">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Username</th>
                                <th>Level</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (! $hasTable): ?>
                                <tr>
                                    <td colspan="5" class="text-center text-danger py-4">Tabel tb_users belum tersedia.</td>
                                </tr>
                            <?php elseif (empty($rows)): ?>
                                <tr>
                                    <td colspan="5" class="text-center text-secondary py-4">Belum ada data pengguna.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($rows as $index => $row): ?>
                                    <?php $isCurrent = ((int) $row['id'] === (int) $currentUserId); ?>
                                    <tr>
                                        <td><?= $index + 1 ?></td>
                                        <td><?= esc((string) ($row['username'] ?? '-')) ?></td>
                                        <td>
                                            <?php if (($row['level'] ?? '') === 'admin'): ?>
                                                <span class="badge text-bg-info">ADMIN</span>
                                            <?php else: ?>
                                                <span class="badge text-bg-secondary">KASIR</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= $isCurrent ? '<span class="text-success">Sedang Login</span>' : '<span class="text-secondary">Aktif</span>' ?></td>
                                        <td>
                                            <div class="d-flex gap-1">
                                                <a href="<?= base_url('/pengguna?edit=' . (int) $row['id']) ?>" class="btn btn-sm btn-outline-info">Edit</a>
                                                <?php if (! $isCurrent): ?>
                                                    <form method="post" action="<?= base_url('/pengguna/delete/' . (int) $row['id']) ?>" onsubmit="return confirm('Hapus pengguna ini?');">
                                                        <?= csrf_field() ?>
                                                        <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                                                    </form>
                                                <?php endif; ?>
                                            </div>
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
