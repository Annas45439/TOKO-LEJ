<?php

namespace App\Libraries;

use App\Models\PrediksiModel;
use App\Models\ProdukModel;
use Config\Database;

class AdvancedPredictionService
{
    protected PrediksiModel $prediksiModel;
    protected ProdukModel $produkModel;
    protected $db;

    public function __construct()
    {
        $this->prediksiModel = new PrediksiModel();
        $this->produkModel = new ProdukModel();
        $this->db = Database::connect();
    }

    /**
     * Generate comprehensive dashboard data with multi-level forecasts
     */
    public function generateDashboardData(): array
    {
        // Total sales forecast
        $totalSalesHistory = $this->prediksiModel->getTotalSalesHistory();
        $totalForecast = $this->forecastWithMetrics($totalSalesHistory);

        // Get top categories and their forecasts
        $categories = $this->getTopCategories(5);
        $categoryForecasts = [];
        
        foreach ($categories as $category) {
            $categoryHistory = $this->prediksiModel->getHistoriesByCategory((int) $category['id']);
            if (count($categoryHistory) >= 3) {
                $categoryForecasts[] = [
                    'category_id' => (int) $category['id'],
                    'category_name' => (string) $category['name'],
                    'forecast' => $this->forecastWithMetrics($categoryHistory),
                ];
            }
        }

        // Get product forecasts (top 10)
        $productForecasts = $this->getTopProductForecasts(10);

        // Detect anomalies in total sales
        $anomalies = $this->prediksiModel->detectAnomalies($totalSalesHistory);

        // Calculate seasonal patterns
        $seasonalPatterns = $this->prediksiModel->calculateSeasonalFactors($totalSalesHistory);

        return [
            'total_forecast' => $totalForecast,
            'category_forecasts' => $categoryForecasts,
            'product_forecasts' => $productForecasts,
            'anomalies' => $anomalies,
            'seasonal_patterns' => $seasonalPatterns,
            'generated_at' => date('Y-m-d H:i:s'),
        ];
    }

    /**
     * Generate stock recommendations based on forecasts
     */
    public function generateStockRecommendations(int $leadTimeDays = 5): array
    {
        $products = $this->produkModel->findAll();
        $recommendations = [];

        foreach ($products as $product) {
            $history = $this->prediksiModel->getHistorisByProduk((int) $product['id']);
            
            if (count($history) < 3) {
                continue;
            }

            $forecast = $this->forecastWithMetrics($history);
            
            if (! $forecast['valid']) {
                continue;
            }

            // Calculate safety stock
            $stdDev = $this->calculateStandardDeviation($history);
            $zScore = 1.65; // 95% service level
            $safetyStock = $zScore * $stdDev * sqrt($leadTimeDays / 30); // Lead time in months

            $nextForecast = (float) $forecast['next_prediction'];
            $recommendedQty = (int) ($nextForecast + $safetyStock);
            $currentStock = (int) ($product['stock'] ?? 0);
            $toBuy = max(0, $recommendedQty - $currentStock);

            $recommendations[] = [
                'product_id' => (int) $product['id'],
                'product_name' => (string) $product['name'],
                'current_stock' => $currentStock,
                'next_forecast' => round($nextForecast, 2),
                'safety_stock' => round($safetyStock, 2),
                'recommended_stock' => $recommendedQty,
                'to_buy' => $toBuy,
                'forecast_accuracy' => round($forecast['metrics']['mape'], 2),
                'trend' => $forecast['trend_analysis']['trend'],
                'priority' => $this->calculatePriority($toBuy, $currentStock, $nextForecast),
            ];
        }

        // Sort by priority (highest to_buy first)
        usort($recommendations, fn ($a, $b) => $b['to_buy'] <=> $a['to_buy']);

        return $recommendations;
    }

    /**
     * Forecast with complete metrics for a given history
     */
    public function forecastWithMetrics(array $history): array
    {
        if (count($history) < 3) {
            return [
                'valid' => false,
                'message' => 'Insufficient data for forecasting',
            ];
        }

        $regression = $this->prediksiModel->calculateRegression($history);
        
        if (! $regression['valid']) {
            return [
                'valid' => false,
                'message' => $regression['message'] ?? 'Regression calculation failed',
            ];
        }

        // Calculate confidence interval
        $ci = $this->prediksiModel->calculateConfidenceInterval(
            $history,
            (float) $regression['a'],
            (float) $regression['b'],
            (float) $regression['nextX']
        );

        // Calculate multi-step forecast
        $multiStepForecast = $this->prediksiModel->calculateMultiStepForecast(
            (float) $regression['a'],
            (float) $regression['b'],
            6 // 6-month forecast
        );

        // Calculate trend strength
        $trendAnalysis = $this->prediksiModel->calculateTrendStrength($history);

        // Calculate seasonal factors
        $seasonalFactors = $this->prediksiModel->calculateSeasonalFactors($history);

        // Detect anomalies
        $anomalies = $this->prediksiModel->detectAnomalies($history);

        return [
            'valid' => true,
            'regression' => [
                'a' => round($regression['a'], 4),
                'b' => round($regression['b'], 4),
                'equation' => "Y' = " . round($regression['a'], 2) . " + " . round($regression['b'], 2) . "X",
            ],
            'metrics' => [
                'mad' => round($regression['mad'], 2),
                'mse' => round($regression['mse'], 2),
                'mape' => round($regression['mape'], 2),
            ],
            'next_prediction' => round($regression['nextPrediction'], 2),
            'confidence_interval' => [
                'prediction' => $ci['prediction'],
                'lower' => $ci['ci_lower'],
                'upper' => $ci['ci_upper'],
                'margin_of_error' => $ci['margin_of_error'],
            ],
            'multi_step_forecast' => $multiStepForecast,
            'trend_analysis' => $trendAnalysis,
            'seasonal_factors' => $seasonalFactors,
            'anomalies' => $anomalies,
            'data_points' => count($history),
        ];
    }

    /**
     * Get top N products with their forecasts
     */
    protected function getTopProductForecasts(int $limit = 10): array
    {
        $products = $this->db->table('tb_products')
            ->select('id, name')
            ->orderBy('id', 'ASC')
            ->limit($limit)
            ->get()
            ->getResultArray();

        $forecasts = [];

        foreach ($products as $product) {
            $history = $this->prediksiModel->getHistorisByProduk((int) $product['id']);
            
            if (count($history) >= 3) {
                $forecast = $this->forecastWithMetrics($history);
                
                if ($forecast['valid']) {
                    $forecasts[] = [
                        'product_id' => (int) $product['id'],
                        'product_name' => (string) $product['name'],
                        'next_forecast' => (float) $forecast['next_prediction'],
                        'confidence_lower' => (float) $forecast['confidence_interval']['lower'],
                        'confidence_upper' => (float) $forecast['confidence_interval']['upper'],
                        'mape' => (float) $forecast['metrics']['mape'],
                        'trend' => (string) $forecast['trend_analysis']['trend'],
                    ];
                }
            }
        }

        return $forecasts;
    }

    /**
     * Get top N categories
     */
    protected function getTopCategories(int $limit = 5): array
    {
        if (! $this->db->tableExists('tb_categories')) {
            return [];
        }

        return $this->db->table('tb_categories')
            ->select('id, name')
            ->orderBy('id', 'ASC')
            ->limit($limit)
            ->get()
            ->getResultArray();
    }

    /**
     * Calculate standard deviation of a dataset
     */
    protected function calculateStandardDeviation(array $history): float
    {
        if (count($history) < 2) {
            return 0.0;
        }

        $values = array_map(fn ($row) => (float) $row['y'], $history);
        $mean = array_sum($values) / count($values);

        $sumSquaredDiff = 0.0;
        foreach ($values as $value) {
            $sumSquaredDiff += ($value - $mean) ** 2;
        }

        $variance = $sumSquaredDiff / count($values);
        return sqrt($variance);
    }

    /**
     * Calculate priority level for stock recommendation
     */
    protected function calculatePriority(int $toBuy, int $currentStock, float $forecast): string
    {
        // Critical: need to buy immediately and current stock is low
        if ($toBuy > $forecast * 0.5 && $currentStock < $forecast * 0.3) {
            return 'critical';
        }

        // High: significant quantity needed
        if ($toBuy > $forecast * 0.3) {
            return 'high';
        }

        // Medium: moderate replenishment needed
        if ($toBuy > 0) {
            return 'medium';
        }

        // Low: stock is sufficient
        return 'low';
    }

    /**
     * Get comparison data for multiple products
     */
    public function getProductComparison(array $productIds): array
    {
        $comparison = [];

        foreach ($productIds as $productId) {
            $product = $this->produkModel->find((int) $productId);
            
            if (! $product) {
                continue;
            }

            $history = $this->prediksiModel->getHistorisByProduk((int) $productId);
            
            if (count($history) >= 3) {
                $forecast = $this->forecastWithMetrics($history);
                
                if ($forecast['valid']) {
                    $comparison[] = [
                        'product_id' => (int) $productId,
                        'product_name' => (string) $product['name'],
                        'forecast' => $forecast,
                    ];
                }
            }
        }

        return $comparison;
    }

    /**
     * Apply seasonal adjustment to a forecast
     */
    public function applySeasonalAdjustment(float $baseForecast, int $month, array $seasonalFactors): float
    {
        if (! isset($seasonalFactors[$month]) || empty($seasonalFactors)) {
            return $baseForecast;
        }

        return round($baseForecast * $seasonalFactors[$month], 2);
    }
}
