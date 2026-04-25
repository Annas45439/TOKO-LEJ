<?php

namespace App\Models;

use Config\Database;

class PrediksiModel
{
    public function getProdukList(): array
    {
        $db = Database::connect();

        if (! $db->tableExists('tb_products')) {
            return [];
        }

        return $db->table('tb_products')
            ->select('id, name')
            ->orderBy('name', 'ASC')
            ->get()
            ->getResultArray();
    }

    public function getHistorisByProduk(int $productId): array
    {
        $db = Database::connect();

        if ($db->tableExists('tb_sales_monthly')) {
            $rows = $this->loadFromSalesMonthly($db, $productId);
            if (count($rows) >= 3) {
                return $rows;
            }
        }

        if ($db->tableExists('tb_transaction_details') && $db->tableExists('tb_transactions')) {
            return $this->loadFromTransactions($db, $productId);
        }

        return [];
    }

    public function calculateRegression(array $historis): array
    {
        $n = count($historis);

        if ($n < 3) {
            return [
                'valid' => false,
                'message' => 'Data historis minimal 3 periode untuk melakukan prediksi.',
                'rows' => $historis,
                'a' => 0.0,
                'b' => 0.0,
                'mad' => 0.0,
                'mse' => 0.0,
                'mape' => 0.0,
                'nextX' => $n + 1,
                'nextPrediction' => 0.0,
                'labels' => [],
                'actualSeries' => [],
                'predictSeries' => [],
            ];
        }

        $sumX = 0.0;
        $sumY = 0.0;
        $sumXY = 0.0;
        $sumX2 = 0.0;

        foreach ($historis as $row) {
            $x = (float) $row['x'];
            $y = (float) $row['y'];
            $sumX += $x;
            $sumY += $y;
            $sumXY += $x * $y;
            $sumX2 += $x * $x;
        }

        $denominator = ($n * $sumX2) - ($sumX * $sumX);

        if (abs($denominator) < 0.000001) {
            return [
                'valid' => false,
                'message' => 'Perhitungan regresi gagal karena penyebaran data tidak memadai.',
                'rows' => $historis,
                'a' => 0.0,
                'b' => 0.0,
                'mad' => 0.0,
                'mse' => 0.0,
                'mape' => 0.0,
                'nextX' => $n + 1,
                'nextPrediction' => 0.0,
                'labels' => [],
                'actualSeries' => [],
                'predictSeries' => [],
            ];
        }

        $b = (($n * $sumXY) - ($sumX * $sumY)) / $denominator;
        $a = ($sumY - ($b * $sumX)) / $n;

        $sumAbsError = 0.0;
        $sumSquaredError = 0.0;
        $sumApe = 0.0;
        $apeCount = 0;

        $labels = [];
        $actualSeries = [];
        $predictSeries = [];
        $detailRows = [];

        foreach ($historis as $row) {
            $x = (float) $row['x'];
            $y = (float) $row['y'];
            $yPred = $a + ($b * $x);
            $error = $y - $yPred;
            $absError = abs($error);
            $errorSquare = $error * $error;
            $ape = null;

            if ($y != 0.0) {
                $ape = abs($error / $y) * 100;
                $sumApe += $ape;
                $apeCount++;
            }

            $sumAbsError += $absError;
            $sumSquaredError += $errorSquare;

            $labels[] = (string) $row['period_label'];
            $actualSeries[] = round($y, 2);
            $predictSeries[] = round($yPred, 2);

            $detailRows[] = [
                'period' => (string) $row['period_label'],
                'x' => (int) $x,
                'y' => round($y, 2),
                'y_pred' => round($yPred, 2),
                'error' => round($error, 2),
                'abs_error' => round($absError, 2),
                'error_square' => round($errorSquare, 2),
                'ape' => $ape === null ? null : round($ape, 2),
            ];
        }

        $mad = $sumAbsError / $n;
        $mse = $sumSquaredError / $n;
        $mape = $apeCount > 0 ? $sumApe / $apeCount : 0.0;

        $nextX = $n + 1;
        $nextPrediction = $a + ($b * $nextX);

        return [
            'valid' => true,
            'message' => null,
            'rows' => $detailRows,
            'a' => round($a, 4),
            'b' => round($b, 4),
            'mad' => round($mad, 4),
            'mse' => round($mse, 4),
            'mape' => round($mape, 2),
            'nextX' => $nextX,
            'nextPrediction' => round($nextPrediction, 2),
            'labels' => $labels,
            'actualSeries' => $actualSeries,
            'predictSeries' => $predictSeries,
        ];
    }

    private function loadFromSalesMonthly($db, int $productId): array
    {
        $fields = $db->getFieldNames('tb_sales_monthly');

        if (! in_array('product_id', $fields, true)) {
            return [];
        }

        $qtyColumn = $this->pickFirstColumn($fields, ['qty', 'quantity', 'total_qty', 'sales_qty', 'jumlah']);

        if ($qtyColumn === null) {
            return [];
        }

        $monthColumn = $this->pickFirstColumn($fields, ['month', 'bulan']);
        $yearColumn = $this->pickFirstColumn($fields, ['year', 'tahun']);
        $periodColumn = $this->pickFirstColumn($fields, ['period', 'periode', 'month_label']);

        $builder = $db->table('tb_sales_monthly')->where('product_id', $productId);

        if ($yearColumn !== null) {
            $builder->orderBy($yearColumn, 'ASC');
        }

        if ($monthColumn !== null) {
            $builder->orderBy($monthColumn, 'ASC');
        }

        if ($periodColumn !== null && $yearColumn === null && $monthColumn === null) {
            $builder->orderBy($periodColumn, 'ASC');
        }

        $rows = $builder->get()->getResultArray();

        $result = [];
        $x = 1;

        foreach ($rows as $row) {
            $qty = (float) ($row[$qtyColumn] ?? 0);

            if ($monthColumn !== null && $yearColumn !== null) {
                $month = str_pad((string) ((int) ($row[$monthColumn] ?? 1)), 2, '0', STR_PAD_LEFT);
                $year = (string) ($row[$yearColumn] ?? '0000');
                $periodLabel = $month . '/' . $year;
            } elseif ($periodColumn !== null) {
                $periodLabel = (string) ($row[$periodColumn] ?? ('P' . $x));
            } else {
                $periodLabel = 'P' . $x;
            }

            $result[] = [
                'x' => $x,
                'y' => $qty,
                'period_label' => $periodLabel,
            ];
            $x++;
        }

        return $result;
    }

    private function loadFromTransactions($db, int $productId): array
    {
        $tFields = $db->getFieldNames('tb_transactions');
        $dateColumn = in_array('date', $tFields, true) ? 'date' : (in_array('transaction_date', $tFields, true) ? 'transaction_date' : null);

        if ($dateColumn === null) {
            return [];
        }

        $rows = $db->table('tb_transaction_details td')
            ->select("DATE_FORMAT(t.$dateColumn, '%Y-%m') AS period_key, DATE_FORMAT(MIN(t.$dateColumn), '%m/%Y') AS period_label, SUM(td.qty) AS qty", false)
            ->join('tb_transactions t', 't.id = td.transaction_id', 'inner')
            ->where('td.product_id', $productId)
            ->groupBy("DATE_FORMAT(t.$dateColumn, '%Y-%m')", false)
            ->orderBy('period_key', 'ASC')
            ->get()
            ->getResultArray();

        if (count($rows) >= 3) {
            return $this->mapSeriesRows($rows, 'period_label', 'qty');
        }

        // Fallback untuk data demo: gunakan urutan transaksi per produk jika data bulanan belum cukup.
        $rowsByTransaction = $db->table('tb_transaction_details td')
            ->select("t.id AS transaction_id, t.invoice_no, DATE_FORMAT(t.$dateColumn, '%d/%m/%Y') AS trx_date, SUM(td.qty) AS qty", false)
            ->join('tb_transactions t', 't.id = td.transaction_id', 'inner')
            ->where('td.product_id', $productId)
            ->groupBy('t.id, t.invoice_no, t.' . $dateColumn)
            ->orderBy('t.' . $dateColumn, 'ASC')
            ->orderBy('t.id', 'ASC')
            ->get()
            ->getResultArray();

        $series = [];
        $x = 1;

        foreach ($rowsByTransaction as $row) {
            $invoice = (string) ($row['invoice_no'] ?? ('TRX-' . $x));
            $date = (string) ($row['trx_date'] ?? '-');

            $series[] = [
                'x' => $x,
                'y' => (float) ($row['qty'] ?? 0),
                'period_label' => $date . ' | ' . $invoice,
            ];
            $x++;
        }

        return $series;
    }

    private function mapSeriesRows(array $rows, string $labelKey, string $qtyKey): array
    {
        $result = [];
        $x = 1;

        foreach ($rows as $row) {
            $result[] = [
                'x' => $x,
                'y' => (float) ($row[$qtyKey] ?? 0),
                'period_label' => (string) ($row[$labelKey] ?? ('P' . $x)),
            ];
            $x++;
        }

        return $result;
    }

    private function pickFirstColumn(array $fields, array $candidates): ?string
    {
        foreach ($candidates as $candidate) {
            if (in_array($candidate, $fields, true)) {
                return $candidate;
            }
        }

        return null;
    }

    /**
     * Get sales history grouped by category
     */
    public function getHistoriesByCategory(int $categoryId): array
    {
        $db = Database::connect();

        if (! $db->tableExists('tb_transaction_details') || ! $db->tableExists('tb_transactions') || ! $db->tableExists('tb_products')) {
            return [];
        }

        $dateColumn = $this->getDateColumn($db, 'tb_transactions');
        if ($dateColumn === null) {
            return [];
        }

        $rows = $db->table('tb_transaction_details td')
            ->select("DATE_FORMAT(t.$dateColumn, '%Y-%m') AS period_key, DATE_FORMAT(MIN(t.$dateColumn), '%m/%Y') AS period_label, SUM(td.qty) AS qty", false)
            ->join('tb_transactions t', 't.id = td.transaction_id', 'inner')
            ->join('tb_products p', 'p.id = td.product_id', 'inner')
            ->where('p.category_id', $categoryId)
            ->groupBy("DATE_FORMAT(t.$dateColumn, '%Y-%m')", false)
            ->orderBy('period_key', 'ASC')
            ->get()
            ->getResultArray();

        return $this->mapSeriesRows($rows, 'period_label', 'qty');
    }

    /**
     * Get total sales history for all products
     */
    public function getTotalSalesHistory(): array
    {
        $db = Database::connect();

        if (! $db->tableExists('tb_transaction_details') || ! $db->tableExists('tb_transactions')) {
            return [];
        }

        $dateColumn = $this->getDateColumn($db, 'tb_transactions');
        if ($dateColumn === null) {
            return [];
        }

        $rows = $db->table('tb_transaction_details td')
            ->select("DATE_FORMAT(t.$dateColumn, '%Y-%m') AS period_key, DATE_FORMAT(MIN(t.$dateColumn), '%m/%Y') AS period_label, SUM(td.qty) AS qty", false)
            ->join('tb_transactions t', 't.id = td.transaction_id', 'inner')
            ->groupBy("DATE_FORMAT(t.$dateColumn, '%Y-%m')", false)
            ->orderBy('period_key', 'ASC')
            ->get()
            ->getResultArray();

        return $this->mapSeriesRows($rows, 'period_label', 'qty');
    }

    /**
     * Get helper to find date column name
     */
    private function getDateColumn($db, string $table): ?string
    {
        $fields = $db->getFieldNames($table);
        
        foreach (['date', 'transaction_date', 'created_at'] as $col) {
            if (in_array($col, $fields, true)) {
                return $col;
            }
        }

        return null;
    }

    /**
     * Calculate seasonal factors (indices) for each month
     * Returns array of [month => seasonal_factor]
     * Factor > 1.0 means above average, < 1.0 means below average
     */
    public function calculateSeasonalFactors(array $historis): array
    {
        if (count($historis) < 12) {
            return []; // Need at least 12 months of data
        }

        // Calculate overall average
        $totalQty = 0.0;
        foreach ($historis as $row) {
            $totalQty += (float) $row['y'];
        }
        $avgQty = $totalQty / count($historis);

        // Extract month from period_label (format: MM/YYYY)
        $monthlyData = [];
        foreach ($historis as $row) {
            $label = (string) $row['period_label'];
            $parts = explode('/', $label);
            if (count($parts) === 2) {
                $month = (int) $parts[0];
                $qty = (float) $row['y'];
                
                if (! isset($monthlyData[$month])) {
                    $monthlyData[$month] = [];
                }
                $monthlyData[$month][] = $qty;
            }
        }

        // Calculate seasonal factor for each month
        $seasonalFactors = [];
        for ($m = 1; $m <= 12; $m++) {
            if (isset($monthlyData[$m]) && count($monthlyData[$m]) > 0) {
                $monthAvg = array_sum($monthlyData[$m]) / count($monthlyData[$m]);
                $seasonalFactors[$m] = round($monthAvg / $avgQty, 4);
            } else {
                $seasonalFactors[$m] = 1.0;
            }
        }

        return $seasonalFactors;
    }

    /**
     * Detect anomalies using IQR (Interquartile Range) method
     * Returns array of [index => anomaly_info]
     */
    public function detectAnomalies(array $historis): array
    {
        if (count($historis) < 4) {
            return [];
        }

        $values = array_map(fn ($row) => (float) $row['y'], $historis);
        sort($values);

        $n = count($values);
        $q1Index = (int) (($n + 1) * 0.25) - 1;
        $q3Index = (int) (($n + 1) * 0.75) - 1;

        $q1 = $values[$q1Index] ?? $values[0];
        $q3 = $values[$q3Index] ?? $values[$n - 1];

        $iqr = $q3 - $q1;
        $lowerBound = $q1 - (1.5 * $iqr);
        $upperBound = $q3 + (1.5 * $iqr);

        $anomalies = [];
        foreach ($historis as $idx => $row) {
            $y = (float) $row['y'];
            if ($y < $lowerBound || $y > $upperBound) {
                $anomalies[$idx] = [
                    'period' => (string) $row['period_label'],
                    'value' => $y,
                    'type' => $y > $upperBound ? 'spike' : 'drop',
                    'severity' => $y > $upperBound ? round(($y - $q3) / $iqr, 2) : round(($q1 - $y) / $iqr, 2),
                ];
            }
        }

        return $anomalies;
    }

    /**
     * Calculate confidence interval for regression predictions
     */
    public function calculateConfidenceInterval(array $historis, float $a, float $b, float $nextX, float $confidenceLevel = 0.95): array
    {
        $n = count($historis);
        if ($n < 3) {
            return [
                'prediction' => $a + ($b * $nextX),
                'ci_lower' => 0.0,
                'ci_upper' => 0.0,
                'margin_of_error' => 0.0,
            ];
        }

        // Calculate residual standard error
        $sumYError = 0.0;
        $sumX = 0.0;
        $sumX2 = 0.0;

        foreach ($historis as $row) {
            $x = (float) $row['x'];
            $y = (float) $row['y'];
            $yPred = $a + ($b * $x);
            $error = $y - $yPred;
            $sumYError += $error * $error;
            $sumX += $x;
            $sumX2 += $x * $x;
        }

        $mse = $sumYError / ($n - 2);
        $se = sqrt($mse);

        // Calculate standard error of prediction
        $xMean = $sumX / $n;
        $sumXX2 = 0.0;
        foreach ($historis as $row) {
            $x = (float) $row['x'];
            $sumXX2 += ($x - $xMean) * ($x - $xMean);
        }

        $sePredict = $se * sqrt(1 + (1 / $n) + (($nextX - $xMean) * ($nextX - $xMean)) / $sumXX2);

        // Get t-value for confidence level (simplified: use ~2.0 for 95% with large n)
        $tValue = 1.96; // Approximation for 95% CI with large sample sizes
        if ($n < 30) {
            // Simplified t-distribution for smaller samples
            $tValue = 2.045; // ~0.95 CI for n=20
        }

        $prediction = $a + ($b * $nextX);
        $marginOfError = $tValue * $sePredict;

        return [
            'prediction' => round($prediction, 2),
            'ci_lower' => round($prediction - $marginOfError, 2),
            'ci_upper' => round($prediction + $marginOfError, 2),
            'margin_of_error' => round($marginOfError, 2),
            'se' => round($se, 4),
        ];
    }

    /**
     * Calculate multi-step forecast (e.g., next 3, 6, or 12 months)
     */
    public function calculateMultiStepForecast(float $a, float $b, int $periods = 3): array
    {
        $n = count([]) + 1; // Start from next period
        $forecast = [];

        for ($i = 0; $i < $periods; $i++) {
            $x = $n + $i;
            $yPred = $a + ($b * $x);
            $forecast[] = [
                'period' => $i + 1,
                'x' => $x,
                'prediction' => round($yPred, 2),
            ];
        }

        return $forecast;
    }

    /**
     * Calculate trend strength and direction
     * Returns R-squared, slope significance, and trend direction
     */
    public function calculateTrendStrength(array $historis): array
    {
        $n = count($historis);
        if ($n < 3) {
            return [
                'r_squared' => 0.0,
                'slope_significant' => false,
                'trend' => 'insufficient_data',
                'slope' => 0.0,
            ];
        }

        // Calculate regression stats
        $sumX = 0.0;
        $sumY = 0.0;
        $sumXY = 0.0;
        $sumX2 = 0.0;
        $sumY2 = 0.0;

        foreach ($historis as $row) {
            $x = (float) $row['x'];
            $y = (float) $row['y'];
            $sumX += $x;
            $sumY += $y;
            $sumXY += $x * $y;
            $sumX2 += $x * $x;
            $sumY2 += $y * $y;
        }

        $numerator = ($n * $sumXY) - ($sumX * $sumY);
        $denominator = sqrt((($n * $sumX2) - ($sumX * $sumX)) * (($n * $sumY2) - ($sumY * $sumY)));

        if (abs($denominator) < 0.000001) {
            return [
                'r_squared' => 0.0,
                'slope_significant' => false,
                'trend' => 'flat',
                'slope' => 0.0,
            ];
        }

        $r = $numerator / $denominator;
        $rSquared = $r * $r;

        // Determine trend direction from slope
        $b = (($n * $sumXY) - ($sumX * $sumY)) / (($n * $sumX2) - ($sumX * $sumX));
        $trend = $b > 0 ? 'upward' : ($b < 0 ? 'downward' : 'flat');
        $slopeSignificant = $rSquared > 0.3; // R² > 0.3 indicates significant slope

        return [
            'r_squared' => round($rSquared, 4),
            'slope_significant' => $slopeSignificant,
            'trend' => $trend,
            'slope' => round($b, 4),
        ];
    }
}
