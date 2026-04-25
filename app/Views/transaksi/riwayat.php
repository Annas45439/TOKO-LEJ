<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('content') ?>
    <div class="panel">
        <div class="d-flex flex-column flex-lg-row justify-content-between gap-3 mb-3">
            <div>
                <div class="panel-title mb-1">Riwayat Transaksi</div>
                <p class="panel-muted mb-0">Daftar transaksi penjualan yang telah tersimpan.</p>
            </div>
            <a href="<?= base_url('/transaksi') ?>" class="btn btn-info">+ Transaksi Baru</a>
        </div>

        <form method="get" action="<?= base_url('/transaksi/riwayat') ?>" class="row g-2 mb-3">
            <div class="col-12 col-md-4">
                <input type="date" name="date" value="<?= esc((string) ($dateFilter ?? '')) ?>" class="form-control">
            </div>
            <div class="col-12 col-md-2 d-grid">
                <button type="submit" class="btn btn-outline-light">Filter</button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-dark table-hover align-middle" style="--bs-table-bg: transparent; --bs-table-hover-bg: rgba(255,255,255,0.06);">
                <thead>
                    <tr>
                        <th>Invoice</th>
                        <th>Tanggal</th>
                        <th>Pelanggan</th>
                        <th>Kasir</th>
                        <th>Metode</th>
                        <th>Total</th>
                        <th>Bayar</th>
                        <th>Kembali</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($transactions)): ?>
                        <tr>
                            <td colspan="9" class="text-center text-secondary py-4">Belum ada transaksi.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($transactions as $transaction): ?>
                            <tr>
                                <td><?= esc((string) $transaction['invoice_no']) ?></td>
                                <td><?= esc((string) $transaction['date']) ?></td>
                                <td><?= esc((string) ($transaction['customer_name'] ?? '-')) ?></td>
                                <td><?= esc((string) ($transaction['cashier_name'] ?? '-')) ?></td>
                                <td><?= esc((string) $transaction['payment_method']) ?></td>
                                <td>Rp <?= number_format((float) $transaction['total'], 0, ',', '.') ?></td>
                                <td>Rp <?= number_format((float) $transaction['payment_amount'], 0, ',', '.') ?></td>
                                <td>Rp <?= number_format((float) $transaction['change_amount'], 0, ',', '.') ?></td>
                                <td><span class="badge text-bg-success"><?= esc((string) $transaction['status']) ?></span></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
<?= $this->endSection() ?>