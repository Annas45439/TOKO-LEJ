<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('content') ?>
    <div class="panel mb-3">
        <div class="d-flex flex-column flex-lg-row justify-content-between gap-3 mb-3">
            <div>
                <div class="panel-title mb-1">Prediksi Penjualan</div>
                <p class="panel-muted mb-0">Perhitungan linear regression berdasarkan data historis penjualan produk.</p>
            </div>
        </div>

        <form method="get" action="<?= base_url('/prediksi') ?>" class="row g-2 align-items-end">
            <div class="col-12 col-md-8 col-lg-6">
                <label class="form-label">Pilih Produk</label>
                <select name="product_id" class="form-select" required>
                    <option value="">Pilih produk</option>
                    <?php foreach ($products as $product): ?>
                        <option value="<?= (int) $product['id'] ?>" <?= ((int) $selectedProductId === (int) $product['id']) ? 'selected' : '' ?>>
                            <?= esc((string) $product['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-12 col-md-4 col-lg-3 d-grid">
                <button type="submit" class="btn btn-info">Hitung Prediksi</button>
            </div>
        </form>
    </div>

    <!-- Quick Overview Section (shown when no product selected) -->
    <?php if ($selectedProductId === 0 && $overview !== null): ?>
        <div class="row g-3 mb-3">
            <!-- Total Sales Forecast -->
            <?php if ($overview['total_forecast'] && $overview['total_forecast']['valid']): ?>
            <div class="col-12 col-lg-6">
                <div class="panel h-100">
                    <div class="panel-title mb-2">📊 Total Sales Forecast (Next Month)</div>
                    <div class="row g-2">
                        <div class="col-6">
                            <div class="rounded-3 p-3" style="background: rgba(13, 110, 253, 0.1); border:1px solid rgba(13, 110, 253, 0.3);">
                                <div class="small text-secondary">Forecast</div>
                                <div class="fw-bold text-info"><?= number_format((int)$overview['total_forecast']['next_prediction'], 0, ',', '.') ?></div>
                                <small class="text-muted">units</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="rounded-3 p-3" style="background: rgba(40, 167, 69, 0.1); border:1px solid rgba(40, 167, 69, 0.3);">
                                <div class="small text-secondary">Accuracy (MAPE)</div>
                                <div class="fw-bold text-success"><?= htmlspecialchars($overview['total_forecast']['metrics']['mape']) ?>%</div>
                                <small class="text-muted">lower is better</small>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="rounded-3 p-2" style="background: rgba(102, 102, 255, 0.1); border:1px solid rgba(102, 102, 255, 0.3);">
                                <small class="text-secondary">95% Confidence: <?= htmlspecialchars($overview['total_forecast']['confidence_interval']['lower']) ?> - <?= htmlspecialchars($overview['total_forecast']['confidence_interval']['upper']) ?></small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Recommendations Summary -->
            <div class="col-12 col-lg-6">
                <div class="panel h-100">
                    <div class="panel-title mb-2">📦 Stock Recommendations</div>
                    <div class="row g-2">
                        <div class="col-6">
                            <div class="rounded-3 p-3" style="background: rgba(220, 53, 69, 0.1); border:1px solid rgba(220, 53, 69, 0.3);">
                                <div class="small text-secondary">🔴 Critical</div>
                                <div class="fw-bold text-danger"><?= htmlspecialchars($overview['critical_recommendations']) ?></div>
                                <small class="text-muted">urgent action</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="rounded-3 p-3" style="background: rgba(255, 193, 7, 0.1); border:1px solid rgba(255, 193, 7, 0.3);">
                                <div class="small text-secondary">🟡 High</div>
                                <div class="fw-bold text-warning"><?= htmlspecialchars($overview['high_recommendations']) ?></div>
                                <small class="text-muted">soon needed</small>
                            </div>
                        </div>
                        <div class="col-12">
                            <a href="<?= base_url('/prediksi/dashboard') ?>" class="btn btn-sm btn-outline-info w-100">
                                📊 View Full Dashboard →
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top 5 Products by Forecast -->
        <div class="panel mb-3">
            <div class="panel-title mb-2">⭐ Top 5 Products by Forecast</div>
            <div class="table-responsive">
                <table class="table table-dark table-hover align-middle" style="--bs-table-bg: transparent; --bs-table-hover-bg: rgba(255,255,255,0.06);">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th class="text-center">Next Forecast</th>
                            <th class="text-center">Trend</th>
                            <th class="text-center">MAPE</th>
                            <th class="text-center">Anomalies</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($overview['top_products'])): ?>
                            <?php foreach ($overview['top_products'] as $product): ?>
                            <tr>
                                <td><strong><?= esc((string) $product['name']) ?></strong></td>
                                <td class="text-center"><?= number_format((int)$product['forecast'], 0, ',', '.') ?></td>
                                <td class="text-center">
                                    <span class="badge bg-<?= $product['trend'] === 'upward' ? 'success' : ($product['trend'] === 'downward' ? 'danger' : 'secondary') ?>">
                                        <?= htmlspecialchars(ucfirst($product['trend'])) ?>
                                    </span>
                                </td>
                                <td class="text-center"><?= htmlspecialchars($product['mape']) ?>%</td>
                                <td class="text-center">
                                    <?php if ($product['has_anomalies']): ?>
                                        <span class="badge bg-warning">⚠️ Yes</span>
                                    <?php else: ?>
                                        <span class="badge bg-success">✓ No</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <a href="<?= base_url('/prediksi?product_id=' . $product['id']) ?>" class="btn btn-xs btn-info">Detail</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted py-3">No data available yet</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Products with Anomalies -->
        <?php if (!empty($overview['anomaly_products'])): ?>
        <div class="panel mb-3">
            <div class="panel-title mb-2">⚠️ Products with Detected Anomalies</div>
            <div class="table-responsive">
                <table class="table table-dark table-hover align-middle" style="--bs-table-bg: transparent; --bs-table-hover-bg: rgba(255,255,255,0.06);">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th class="text-center">Anomaly Count</th>
                            <th class="text-center">Latest Type</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($overview['anomaly_products'] as $product): ?>
                        <tr>
                            <td><strong><?= esc((string) $product['name']) ?></strong></td>
                            <td class="text-center">
                                <span class="badge bg-warning"><?= htmlspecialchars($product['anomaly_count']) ?></span>
                            </td>
                            <td class="text-center">
                                <?php if ($product['latest_anomaly']): ?>
                                    <span class="badge bg-<?= $product['latest_anomaly']['type'] === 'spike' ? 'danger' : 'warning' ?>">
                                        <?= htmlspecialchars(ucfirst($product['latest_anomaly']['type'])) ?>
                                    </span>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <a href="<?= base_url('/prediksi?product_id=' . $product['id']) ?>" class="btn btn-xs btn-warning">Investigate</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php endif; ?>

    <?php elseif ($selectedProductId > 0 && $result !== null): ?>
        <?php if (! $result['valid']): ?>
            <div class="alert alert-warning">
                <?= esc((string) ($result['message'] ?? 'Data historis belum mencukupi untuk diproses.')) ?>
            </div>
        <?php else: ?>
            <div class="row g-3 mb-3">
                <div class="col-12 col-lg-6">
                    <div class="panel h-100">
                        <div class="panel-title mb-2">Persamaan Regresi</div>
                        <div class="fs-4 fw-bold text-info">Y' = <?= esc((string) $result['a']) ?> + (<?= esc((string) $result['b']) ?> x X)</div>
                        <p class="panel-muted mt-2 mb-0">Prediksi periode berikutnya (X = <?= esc((string) $result['nextX']) ?>): <strong class="text-success"><?= esc((string) $result['nextPrediction']) ?></strong></p>
                    </div>
                </div>
                <div class="col-12 col-lg-6">
                    <div class="panel h-100">
                        <div class="panel-title mb-2">Metrik Akurasi</div>
                        <div class="row g-2">
                            <div class="col-4">
                                <div class="rounded-3 p-3" style="background: rgba(255,255,255,0.05); border:1px solid rgba(255,255,255,0.09);">
                                    <div class="small text-secondary">MAD</div>
                                    <div class="fw-bold"><?= esc((string) $result['mad']) ?></div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="rounded-3 p-3" style="background: rgba(255,255,255,0.05); border:1px solid rgba(255,255,255,0.09);">
                                    <div class="small text-secondary">MSE</div>
                                    <div class="fw-bold"><?= esc((string) $result['mse']) ?></div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="rounded-3 p-3" style="background: rgba(255,255,255,0.05); border:1px solid rgba(255,255,255,0.09);">
                                    <div class="small text-secondary">MAPE</div>
                                    <div class="fw-bold"><?= esc((string) $result['mape']) ?>%</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Advanced Forecast Features -->
            <?php if ($advancedForecast && $advancedForecast['valid']): ?>
                <div class="row g-3 mb-3">
                    <!-- Confidence Interval -->
                    <div class="col-12 col-lg-4">
                        <div class="panel h-100">
                            <div class="panel-title mb-2">📊 Confidence Interval (95%)</div>
                            <div class="rounded-3 p-3" style="background: rgba(52, 211, 153, 0.1); border:1px solid rgba(52, 211, 153, 0.3);">
                                <div class="small text-secondary mb-2">Prediksi: <strong><?= esc((string) $advancedForecast['confidence_interval']['prediction']) ?></strong></div>
                                <div class="small text-secondary mb-2">Range: <?= esc((string) $advancedForecast['confidence_interval']['lower']) ?> - <?= esc((string) $advancedForecast['confidence_interval']['upper']) ?></div>
                                <div class="small text-secondary">Margin of Error: ±<?= esc((string) $advancedForecast['confidence_interval']['margin_of_error']) ?></div>
                            </div>
                        </div>
                    </div>

                    <!-- Trend Analysis -->
                    <div class="col-12 col-lg-4">
                        <div class="panel h-100">
                            <div class="panel-title mb-2">📈 Trend Analysis</div>
                            <div class="rounded-3 p-3" style="background: rgba(96, 165, 250, 0.1); border:1px solid rgba(96, 165, 250, 0.3);">
                                <div class="small text-secondary mb-2">Trend: <strong><?= htmlspecialchars(ucfirst($advancedForecast['trend_analysis']['trend'])); ?></strong></div>
                                <div class="small text-secondary mb-2">R² Score: <?= esc((string) $advancedForecast['trend_analysis']['r_squared']) ?></div>
                                <div class="small text-secondary">Slope: <?= esc((string) $advancedForecast['trend_analysis']['slope']) ?></div>
                            </div>
                        </div>
                    </div>

                    <!-- Data Quality -->
                    <div class="col-12 col-lg-4">
                        <div class="panel h-100">
                            <div class="panel-title mb-2">🔍 Data Quality</div>
                            <div class="rounded-3 p-3" style="background: rgba(168, 85, 247, 0.1); border:1px solid rgba(168, 85, 247, 0.3);">
                                <div class="small text-secondary mb-2">Data Points: <strong><?= esc((string) $advancedForecast['data_points']) ?></strong></div>
                                <div class="small text-secondary mb-2">Anomalies: <?= count($advancedForecast['anomalies']) ?></div>
                                <div class="small text-secondary">Status: <?= count($advancedForecast['anomalies']) > 0 ? '⚠️ Has Outliers' : '✅ Clean Data' ?></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Multi-Step Forecast -->
                <?php if (!empty($advancedForecast['multi_step_forecast'])): ?>
                <div class="panel mb-3">
                    <div class="panel-title mb-2">📅 6-Month Forecast</div>
                    <div class="table-responsive">
                        <table class="table table-dark table-hover align-middle" style="--bs-table-bg: transparent; --bs-table-hover-bg: rgba(255,255,255,0.06);">
                            <thead>
                                <tr>
                                    <th>Period Ahead</th>
                                    <th>X</th>
                                    <th>Forecast</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($advancedForecast['multi_step_forecast'] as $forecast): ?>
                                <tr>
                                    <td>Month +<?= esc((string) $forecast['period']) ?></td>
                                    <td><?= esc((string) $forecast['x']) ?></td>
                                    <td><strong><?= esc((string) $forecast['prediction']) ?></strong></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Anomalies -->
                <?php if (!empty($advancedForecast['anomalies'])): ?>
                <div class="panel mb-3">
                    <div class="panel-title mb-2">⚠️ Detected Anomalies</div>
                    <div class="table-responsive">
                        <table class="table table-dark table-hover align-middle" style="--bs-table-bg: transparent; --bs-table-hover-bg: rgba(255,255,255,0.06);">
                            <thead>
                                <tr>
                                    <th>Period</th>
                                    <th>Value</th>
                                    <th>Type</th>
                                    <th>Severity</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($advancedForecast['anomalies'] as $anomaly): ?>
                                <tr>
                                    <td><?= esc((string) $anomaly['period']) ?></td>
                                    <td><?= esc((string) $anomaly['value']) ?></td>
                                    <td><span class="badge bg-<?= $anomaly['type'] === 'spike' ? 'danger' : 'warning' ?>"><?= htmlspecialchars(ucfirst($anomaly['type'])); ?></span></td>
                                    <td><?= esc((string) $anomaly['severity']) ?>x IQR</td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php endif; ?>
            <?php endif; ?>

                                <th>Error²</th>
                                <th>APE (%)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($result['rows'] as $row): ?>
                                <tr>
                                    <td><?= esc((string) $row['period']) ?></td>
                                    <td><?= esc((string) $row['x']) ?></td>
                                    <td><?= esc((string) $row['y']) ?></td>
                                    <td><?= esc((string) $row['y_pred']) ?></td>
                                    <td><?= esc((string) $row['error']) ?></td>
                                    <td><?= esc((string) $row['abs_error']) ?></td>
                                    <td><?= esc((string) $row['error_square']) ?></td>
                                    <td><?= $row['ape'] === null ? '-' : esc((string) $row['ape']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script>
                const prediksiLabels = <?= json_encode($result['labels']) ?>;
                const prediksiAktual = <?= json_encode($result['actualSeries']) ?>;
                const prediksiModel = <?= json_encode($result['predictSeries']) ?>;
                const prediksiAbsError = <?= json_encode(array_map(static fn ($row) => (float) ($row['abs_error'] ?? 0), $result['rows'])) ?>;

                const prediksiCtx = document.getElementById('prediksiChart');
                if (prediksiCtx) {
                    new Chart(prediksiCtx, {
                        type: 'line',
                        data: {
                            labels: prediksiLabels,
                            datasets: [{
                                label: 'Aktual',
                                data: prediksiAktual,
                                borderColor: '#00d4ff',
                                backgroundColor: 'rgba(0, 212, 255, 0.18)',
                                tension: 0.3,
                                fill: true,
                            }, {
                                label: 'Prediksi',
                                data: prediksiModel,
                                borderColor: '#7c3aed',
                                backgroundColor: 'rgba(124, 58, 237, 0.08)',
                                borderDash: [6, 4],
                                tension: 0.3,
                                fill: false,
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
                                        color: '#9aa5c4'
                                    },
                                    grid: {
                                        color: 'rgba(255,255,255,0.08)'
                                    }
                                }
                            }
                        }
                    });
                }

                const prediksiErrorCtx = document.getElementById('prediksiErrorChart');
                if (prediksiErrorCtx) {
                    new Chart(prediksiErrorCtx, {
                        type: 'bar',
                        data: {
                            labels: prediksiLabels,
                            datasets: [{
                                label: 'Error Absolut',
                                data: prediksiAbsError,
                                borderColor: '#f59e0b',
                                backgroundColor: 'rgba(245, 158, 11, 0.35)',
                                borderWidth: 1,
                                borderRadius: 6,
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
                                        color: '#9aa5c4'
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
        <?php endif; ?>
    <?php endif; ?>
<?= $this->endSection() ?>
