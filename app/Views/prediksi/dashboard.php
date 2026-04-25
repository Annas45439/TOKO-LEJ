<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('content') ?>

<div class="container-fluid px-4 py-4 prediksi-theme">
    <!-- Header with Filters -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">📊 Dashboard Prediksi Penjualan</h1>
            <small class="text-muted">Generated: <?php echo htmlspecialchars($dashboard['generated_at'] ?? '-'); ?></small>
        </div>
        <div class="btn-group" role="group">
            <button type="button" class="btn btn-sm btn-outline-primary" onclick="exportToCSV()">📥 Export CSV</button>
            <button type="button" class="btn btn-sm btn-outline-success" onclick="exportRecommendationsCSV()">📦 Export Recommendations</button>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row g-2">
                <div class="col-md-3">
                    <label class="form-label">Period</label>
                    <select class="form-select form-select-sm" id="periodFilter" onchange="applyFilters()">
                        <option value="all">All Time</option>
                        <option value="12">Last 12 Months</option>
                        <option value="24">Last 24 Months</option>
                        <option value="6">Last 6 Months</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Category</label>
                    <select class="form-select form-select-sm" id="categoryFilter" onchange="applyFilters()">
                        <option value="">All Categories</option>
                        <?php if (!empty($dashboard['category_forecasts'])): ?>
                            <?php foreach ($dashboard['category_forecasts'] as $cat): ?>
                            <option value="<?php echo htmlspecialchars($cat['category_id']); ?>">
                                <?php echo htmlspecialchars($cat['category_name']); ?>
                            </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Show Anomalies</label>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="anomalyToggle" checked onchange="applyFilters()">
                        <label class="form-check-label" for="anomalyToggle">Include Anomalies</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="form-label">&nbsp;</label>
                    <button class="btn btn-sm btn-info w-100" onclick="resetFilters()">🔄 Reset</button>
                </div>
            </div>
        </div>
    </div>

    <?php if (isset($dashboard['total_forecast']) && $dashboard['total_forecast']['valid']): ?>
        <!-- KPI Cards -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="card bg-primary bg-opacity-10 border-primary">
                    <div class="card-body">
                        <h6 class="card-title text-muted">Next Month Forecast</h6>
                        <h2 class="mb-0">
                            <?php echo number_format((int)$dashboard['total_forecast']['next_prediction'], 0, ',', '.'); ?>
                        </h2>
                        <small class="text-muted">
                            ±<?php echo htmlspecialchars($dashboard['total_forecast']['confidence_interval']['margin_of_error']); ?> (95% CI)
                        </small>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-3">
                <div class="card bg-success bg-opacity-10 border-success">
                    <div class="card-body">
                        <h6 class="card-title text-muted">Forecast Accuracy (MAPE)</h6>
                        <h2 class="mb-0"><?php echo htmlspecialchars($dashboard['total_forecast']['metrics']['mape']); ?>%</h2>
                        <small class="text-muted">
                            <?php 
                            $mape = (float)$dashboard['total_forecast']['metrics']['mape'];
                            echo $mape < 20 ? '✓ Excellent' : ($mape < 50 ? '⚠ Good' : '✗ Fair');
                            ?>
                        </small>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-3">
                <div class="card bg-warning bg-opacity-10 border-warning">
                    <div class="card-body">
                        <h6 class="card-title text-muted">Anomalies Detected</h6>
                        <h2 class="mb-0"><?php echo count($dashboard['anomalies']); ?></h2>
                        <small class="text-muted">
                            <?php 
                            $anomalieCount = count($dashboard['anomalies']);
                            echo $anomalieCount > 0 ? htmlspecialchars(implode(', ', array_map(fn($a) => $a['type'], $dashboard['anomalies']))) : 'None';
                            ?>
                        </small>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-3">
                <div class="card bg-info bg-opacity-10 border-info">
                    <div class="card-body">
                        <h6 class="card-title text-muted">Pending Recommendations</h6>
                        <h2 class="mb-0">
                            <?php echo count(array_filter($recommendations, fn($r) => $r['to_buy'] > 0)); ?>
                        </h2>
                        <small class="text-muted">Products to reorder</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row 1 -->
        <div class="row mb-4">
            <!-- Actual vs Predicted Chart -->
            <div class="col-lg-6 mb-3">
                <div class="card">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">📈 Actual vs Predicted Sales</h6>
                    </div>
                    <div class="card-body">
                        <canvas id="actualVsPredictedChart" height="300"></canvas>
                    </div>
                </div>
            </div>

            <!-- Seasonal Pattern Chart -->
            <div class="col-lg-6 mb-3">
                <div class="card">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">🌍 Seasonal Patterns (Monthly Index)</h6>
                    </div>
                    <div class="card-body">
                        <canvas id="seasonalPatternChart" height="300"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row 2 -->
        <div class="row mb-4">
            <!-- Category Forecast Comparison -->
            <div class="col-lg-6 mb-3">
                <div class="card">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">📊 Category Forecast Comparison (Next Month)</h6>
                    </div>
                    <div class="card-body">
                        <canvas id="categoryForecastChart" height="300"></canvas>
                    </div>
                </div>
            </div>

            <!-- Confidence Interval Chart -->
            <div class="col-lg-6 mb-3">
                <div class="card">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">📉 6-Month Forecast with Confidence Bands</h6>
                    </div>
                    <div class="card-body">
                        <canvas id="confidenceIntervalChart" height="300"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Products Table -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">🏆 Top 10 Products by Forecast</h6>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Product</th>
                                    <th class="text-center">Next Forecast</th>
                                    <th class="text-center">Confidence Range</th>
                                    <th class="text-center">MAPE (%)</th>
                                    <th class="text-center">Trend</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (! empty($dashboard['product_forecasts'])): ?>
                                    <?php foreach ($dashboard['product_forecasts'] as $product): ?>
                                        <tr>
                                            <td>
                                                <strong><?php echo htmlspecialchars($product['product_name']); ?></strong>
                                            </td>
                                            <td class="text-center">
                                                <?php echo number_format((int)$product['next_forecast'], 0, ',', '.'); ?>
                                            </td>
                                            <td class="text-center">
                                                <small class="text-muted">
                                                    <?php echo number_format((int)$product['confidence_lower'], 0, ',', '.'); ?> - 
                                                    <?php echo number_format((int)$product['confidence_upper'], 0, ',', '.'); ?>
                                                </small>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-<?php echo $product['mape'] < 20 ? 'success' : ($product['mape'] < 50 ? 'warning' : 'danger'); ?>">
                                                    <?php echo htmlspecialchars($product['mape']); ?>%
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-<?php echo $product['trend'] === 'upward' ? 'success' : ($product['trend'] === 'downward' ? 'danger' : 'secondary'); ?>">
                                                    <?php echo htmlspecialchars(ucfirst($product['trend'])); ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-3">No products with sufficient data</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stock Recommendations Table -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">📦 Stock Recommendations</h6>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Product</th>
                                    <th class="text-center">Current Stock</th>
                                    <th class="text-center">Next Forecast</th>
                                    <th class="text-center">Recommended Stock</th>
                                    <th class="text-center">To Buy</th>
                                    <th class="text-center">Priority</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (! empty($recommendations)): ?>
                                    <?php foreach ($recommendations as $rec): ?>
                                        <?php if ($rec['to_buy'] > 0): ?>
                                            <tr>
                                                <td>
                                                    <strong><?php echo htmlspecialchars($rec['product_name']); ?></strong>
                                                </td>
                                                <td class="text-center">
                                                    <?php echo number_format((int)$rec['current_stock'], 0, ',', '.'); ?>
                                                </td>
                                                <td class="text-center">
                                                    <?php echo number_format((int)$rec['next_forecast'], 0, ',', '.'); ?>
                                                </td>
                                                <td class="text-center">
                                                    <?php echo number_format((int)$rec['recommended_stock'], 0, ',', '.'); ?>
                                                </td>
                                                <td class="text-center">
                                                    <strong class="text-danger">
                                                        +<?php echo number_format((int)$rec['to_buy'], 0, ',', '.'); ?>
                                                    </strong>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge bg-<?php echo $rec['priority'] === 'critical' ? 'danger' : ($rec['priority'] === 'high' ? 'warning' : 'info'); ?>">
                                                        <?php echo htmlspecialchars(ucfirst($rec['priority'])); ?>
                                                    </span>
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-3">No recommendations at this time</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Anomalies Table -->
        <?php if (! empty($dashboard['anomalies'])): ?>
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">⚠️ Detected Anomalies</h6>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Period</th>
                                    <th class="text-center">Actual Value</th>
                                    <th class="text-center">Type</th>
                                    <th class="text-center">Severity</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($dashboard['anomalies'] as $anomaly): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($anomaly['period']); ?></td>
                                    <td class="text-center"><?php echo number_format((int)$anomaly['value'], 0, ',', '.'); ?></td>
                                    <td class="text-center">
                                        <span class="badge bg-<?php echo $anomaly['type'] === 'spike' ? 'danger' : 'warning'; ?>">
                                            <?php echo htmlspecialchars(ucfirst($anomaly['type'])); ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <strong><?php echo htmlspecialchars($anomaly['severity']); ?>x IQR</strong>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

    <?php else: ?>
        <div class="alert alert-warning" role="alert">
            <h5 class="alert-heading">⚠️ Insufficient Data</h5>
            <p><?php echo htmlspecialchars($dashboard['total_forecast']['message'] ?? 'Unable to generate forecast. Please ensure you have at least 3 months of historical sales data.'); ?></p>
        </div>
    <?php endif; ?>

</div>

<!-- Chart.js Library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const dashboardData = <?php echo json_encode($dashboard); ?>;

    Chart.defaults.color = '#dfe7fb';
    Chart.defaults.borderColor = 'rgba(223, 231, 251, 0.14)';
    Chart.defaults.font.family = 'Plus Jakarta Sans, sans-serif';

    // 1. Actual vs Predicted Chart
    if (dashboardData.total_forecast && dashboardData.total_forecast.valid) {
        const ctx1 = document.getElementById('actualVsPredictedChart');
        if (ctx1) {
            new Chart(ctx1, {
                type: 'line',
                data: {
                    labels: dashboardData.total_forecast.regression ? Object.keys(dashboardData.total_forecast.regression).slice(0, 24) : [],
                    datasets: [
                        {
                            label: 'Actual Sales',
                            data: dashboardData.total_forecast.actualSeries || [],
                            borderColor: '#7c6fff',
                            backgroundColor: 'rgba(124, 111, 255, 0.18)',
                            tension: 0.3,
                            fill: true,
                        },
                        {
                            label: 'Predicted Sales',
                            data: dashboardData.total_forecast.predictSeries || [],
                            borderColor: '#00c7d8',
                            backgroundColor: 'rgba(0, 199, 216, 0.14)',
                            tension: 0.3,
                            fill: true,
                            borderDash: [5, 5],
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: true },
                    },
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            });
        }

        // 2. Seasonal Pattern Chart
        const ctx2 = document.getElementById('seasonalPatternChart');
        if (ctx2 && dashboardData.seasonal_patterns) {
            const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            const factors = Object.values(dashboardData.seasonal_patterns).slice(0, 12);
            
            new Chart(ctx2, {
                type: 'bar',
                data: {
                    labels: months,
                    datasets: [{
                        label: 'Seasonal Factor',
                        data: factors,
                        backgroundColor: 'rgba(124, 111, 255, 0.36)',
                        borderColor: 'rgba(124, 111, 255, 0.85)',
                        borderWidth: 1,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: true },
                    },
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            });
        }

        // 3. Category Forecast Comparison
        const ctx3 = document.getElementById('categoryForecastChart');
        if (ctx3 && dashboardData.category_forecasts) {
            const categoryLabels = dashboardData.category_forecasts.map(c => c.category_name);
            const categoryForecasts = dashboardData.category_forecasts.map(c => c.forecast.next_prediction || 0);

            new Chart(ctx3, {
                type: 'bar',
                data: {
                    labels: categoryLabels,
                    datasets: [{
                        label: 'Next Month Forecast',
                        data: categoryForecasts,
                        backgroundColor: [
                            'rgba(124, 111, 255, 0.52)',
                            'rgba(0, 199, 216, 0.5)',
                            'rgba(120, 130, 170, 0.46)',
                            'rgba(98, 113, 172, 0.5)',
                            'rgba(83, 156, 205, 0.45)',
                        ],
                        borderWidth: 0,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                    },
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            });
        }

        // 4. 6-Month Forecast with Confidence Intervals
        const ctx4 = document.getElementById('confidenceIntervalChart');
        if (ctx4 && dashboardData.total_forecast.multi_step_forecast) {
            const multiStep = dashboardData.total_forecast.multi_step_forecast;
            const labels = multiStep.map((f, i) => 'M+' + (i + 1));
            const predictions = multiStep.map(f => f.prediction);
            
            // Estimate CI upper/lower based on margin of error
            const ciMargin = dashboardData.total_forecast.confidence_interval.margin_of_error;
            const ciUpper = predictions.map(p => p + (ciMargin * Math.sqrt(predictions.length)));
            const ciLower = predictions.map(p => Math.max(0, p - (ciMargin * Math.sqrt(predictions.length))));

            new Chart(ctx4, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Forecast',
                            data: predictions,
                            borderColor: '#7c6fff',
                            backgroundColor: 'rgba(124, 111, 255, 0.2)',
                            tension: 0.3,
                            fill: false,
                            borderWidth: 3,
                        },
                        {
                            label: 'Upper Confidence Limit',
                            data: ciUpper,
                            borderColor: 'rgba(124, 111, 255, 0.42)',
                            borderWidth: 1,
                            borderDash: [5, 5],
                            fill: false,
                        },
                        {
                            label: 'Lower Confidence Limit',
                            data: ciLower,
                            borderColor: 'rgba(124, 111, 255, 0.42)',
                            borderWidth: 1,
                            borderDash: [5, 5],
                            fill: '-1',
                            backgroundColor: 'rgba(124, 111, 255, 0.12)',
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: true },
                    },
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            });
        }
    }
});

// Filter Functions
function applyFilters() {
    console.log('Filters applied');
    // In a real implementation, this would reload the page with filters
    // For now, it's a placeholder for future enhancement
}

function resetFilters() {
    document.getElementById('periodFilter').value = 'all';
    document.getElementById('categoryFilter').value = '';
    document.getElementById('anomalyToggle').checked = true;
    applyFilters();
}

// Export Functions
function exportToCSV() {
    const data = <?php echo json_encode($dashboard); ?>;
    let csv = 'Prediction Dashboard Export\n';
    csv += 'Generated: ' + new Date().toLocaleString() + '\n\n';
    
    // Add total forecast
    csv += 'TOTAL SALES FORECAST\n';
    csv += 'Next Prediction,' + (data.total_forecast?.next_prediction || 'N/A') + '\n';
    csv += 'Confidence Lower,' + (data.total_forecast?.confidence_interval?.lower || 'N/A') + '\n';
    csv += 'Confidence Upper,' + (data.total_forecast?.confidence_interval?.upper || 'N/A') + '\n';
    csv += 'MAPE,' + (data.total_forecast?.metrics?.mape || 'N/A') + '%\n\n';
    
    // Add category forecasts
    csv += 'CATEGORY FORECASTS\n';
    csv += 'Category,Next Forecast,Trend,MAPE\n';
    if (data.category_forecasts) {
        data.category_forecasts.forEach(cat => {
            csv += cat.category_name + ',' + 
                   (cat.forecast?.next_prediction || 'N/A') + ',' +
                   (cat.forecast?.trend_analysis?.trend || 'N/A') + ',' +
                   (cat.forecast?.metrics?.mape || 'N/A') + '\n';
        });
    }
    
    downloadCSV(csv, 'prediction_dashboard_' + new Date().toISOString().split('T')[0] + '.csv');
}

function exportRecommendationsCSV() {
    const recommendations = <?php echo json_encode($recommendations); ?>;
    let csv = 'Stock Recommendations\n';
    csv += 'Generated: ' + new Date().toLocaleString() + '\n\n';
    csv += 'Product,Current Stock,Next Forecast,Recommended Stock,To Buy,Priority,Trend\n';
    
    recommendations.forEach(rec => {
        if (rec.to_buy > 0) {
            csv += rec.product_name + ',' +
                   rec.current_stock + ',' +
                   rec.next_forecast + ',' +
                   rec.recommended_stock + ',' +
                   rec.to_buy + ',' +
                   rec.priority + ',' +
                   rec.trend + '\n';
        }
    });
    
    downloadCSV(csv, 'recommendations_' + new Date().toISOString().split('T')[0] + '.csv');
}

function downloadCSV(content, filename) {
    const link = document.createElement('a');
    link.setAttribute('href', 'data:text/csv;charset=utf-8,' + encodeURIComponent(content));
    link.setAttribute('download', filename);
    link.style.display = 'none';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}
</script>

<?= $this->endSection() ?>
