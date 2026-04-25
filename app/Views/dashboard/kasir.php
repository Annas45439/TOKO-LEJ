<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('content') ?>
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-label">Total Produk</div>
            <div class="stat-value"><?= esc((string) ($stats['products'] ?? 0)) ?></div>
            <div class="stat-foot">Produk tersedia untuk dijual</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Transaksi Hari Ini</div>
            <div class="stat-value"><?= esc((string) ($stats['transactionsToday'] ?? 0)) ?></div>
            <div class="stat-foot">Aktivitas kasir hari ini</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Stok Perhatian</div>
            <div class="stat-value"><?= esc((string) ($stats['lowStock'] ?? 0)) ?></div>
            <div class="stat-foot">Produk hampir habis</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Total Pelanggan</div>
            <div class="stat-value"><?= esc((string) ($stats['customers'] ?? 0)) ?></div>
            <div class="stat-foot">Referensi data pelanggan</div>
        </div>
    </div>

    <div class="panel mb-3">
        <div class="panel-title mb-2">Grafik Transaksi Bulanan</div>
        <p class="panel-muted mb-3">Visual transaksi 6 bulan terakhir.</p>
        <div style="height: 220px;">
            <canvas id="dashboardChartKasir"></canvas>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-12 col-xl-6">
            <div class="panel h-100">
                <div class="panel-title mb-2">Aksi Cepat Kasir</div>
                <p class="panel-muted mb-3">Akses fitur utama tanpa pindah banyak menu.</p>
                <div class="d-grid gap-2">
                    <a href="<?= base_url('/transaksi') ?>" class="btn btn-info">Buka Kasir POS</a>
                    <a href="<?= base_url('/transaksi/riwayat') ?>" class="btn btn-outline-light">Lihat Riwayat Transaksi</a>
                    <a href="<?= base_url('/produk') ?>" class="btn btn-outline-light">Lihat Produk</a>
                </div>
            </div>
        </div>

        <div class="col-12 col-xl-6">
            <div class="panel h-100">
                <div class="panel-title mb-2">Transaksi Terbaru</div>
                <p class="panel-muted mb-3">5 transaksi terakhir untuk monitoring cepat.</p>
                <?php if (empty($stats['recentTransactions'])): ?>
                    <p class="panel-muted mb-0">Belum ada transaksi.</p>
                <?php else: ?>
                    <div class="d-flex flex-column gap-2">
                        <?php foreach ($stats['recentTransactions'] as $trx): ?>
                            <div class="p-2 rounded" style="background: rgba(255,255,255,0.04); border:1px solid rgba(255,255,255,0.08);">
                                <div class="d-flex justify-content-between">
                                    <span class="fw-semibold"><?= esc((string) ($trx['invoice_no'] ?? '-')) ?></span>
                                    <span class="text-info">Rp <?= number_format((float) ($trx['total'] ?? 0), 0, ',', '.') ?></span>
                                </div>
                                <small class="text-secondary"><?= esc((string) ($trx['payment_method'] ?? '-')) ?> | <?= esc((string) ($trx['date'] ?? $trx['transaction_date'] ?? '-')) ?></small>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const chartLabelsKasir = <?= json_encode($stats['monthlyLabels'] ?? []) ?>;
        const chartValuesKasir = <?= json_encode($stats['monthlyTotals'] ?? []) ?>;

        const ctxKasir = document.getElementById('dashboardChartKasir');
        if (ctxKasir) {
            new Chart(ctxKasir, {
                type: 'line',
                data: {
                    labels: chartLabelsKasir,
                    datasets: [{
                        label: 'Transaksi',
                        data: chartValuesKasir,
                        borderColor: '#7c3aed',
                        backgroundColor: 'rgba(124, 58, 237, 0.18)',
                        fill: true,
                        tension: 0.35,
                        pointRadius: 3,
                        pointHoverRadius: 5,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            labels: {
                                color: '#f0f4ff'
                            }
                        }
                    },
                    scales: {
                        x: {
                            ticks: {
                                color: '#9aa5c4'
                            },
                            grid: {
                                color: 'rgba(255,255,255,0.08)'
                            }
                        },
                        y: {
                            beginAtZero: true,
                            ticks: {
                                color: '#9aa5c4',
                                precision: 0,
                            },
                            grid: {
                                color: 'rgba(255,255,255,0.08)'
                            }
                        }
                    }
                }
            });
        }
    </script>
<?= $this->endSection() ?>