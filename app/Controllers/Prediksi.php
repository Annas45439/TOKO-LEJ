<?php

namespace App\Controllers;

use App\Libraries\AdvancedPredictionService;
use App\Models\PrediksiModel;

class Prediksi extends BaseController
{
    protected AdvancedPredictionService $predictionService;

    public function __construct()
    {
        $this->predictionService = new AdvancedPredictionService();
    }

    /**
     * Single product prediction view (existing)
     */
    public function index()
    {
        $requestedProductId = $this->request->getGet('product_id');
        $productId = is_numeric($requestedProductId) ? (int) $requestedProductId : 0;
        $model = new PrediksiModel();

        $products = $model->getProdukList();
        $result = null;
        $advancedForecast = null;
        $selectedProductName = null;
        $userNotice = null;
        
        // Overview data when no product selected
        $overview = null;

        if ($requestedProductId !== null && $requestedProductId !== '' && $productId <= 0) {
            $userNotice = 'Produk yang dipilih tidak valid. Silakan pilih produk dari daftar.';
        }

        if ($productId > 0) {
            foreach ($products as $product) {
                if ((int) ($product['id'] ?? 0) === $productId) {
                    $selectedProductName = (string) ($product['name'] ?? 'Produk');
                    break;
                }
            }

            if ($selectedProductName === null) {
                $userNotice = 'Produk yang dipilih tidak ditemukan.';
                $productId = 0;
            }
        }

        if ($productId > 0) {
            $historis = $model->getHistorisByProduk($productId);
            $result = $model->calculateRegression($historis);

            if (! ($result['valid'] ?? false)) {
                $userNotice = (string) ($result['message'] ?? 'Data historis belum cukup untuk menampilkan prediksi.');
            }

            // Get advanced forecast data
            if (count($historis) >= 3) {
                $advancedForecast = $this->predictionService->forecastWithMetrics($historis);
            }
        } else {
            // Load overview data for empty state
            $overview = $this->generateQuickOverview();
        }

        return view('prediksi/index', [
            'title' => 'Prediksi Penjualan',
            'username' => (string) session()->get('username'),
            'level' => (string) session()->get('level'),
            'activeMenu' => 'prediksi',
            'products' => $products,
            'selectedProductId' => $productId,
            'selectedProductName' => $selectedProductName,
            'result' => $result,
            'advancedForecast' => $advancedForecast,
            'overview' => $overview,
            'userNotice' => $userNotice,
        ]);
    }

    /**
     * Generate quick overview data for empty state
     */
    protected function generateQuickOverview(): array
    {
        $model = new PrediksiModel();
        $produkModel = new \App\Models\ProdukModel();
        
        // Get total sales forecast
        $totalHistory = $model->getTotalSalesHistory();
        $totalForecast = null;
        if (count($totalHistory) >= 3) {
            $totalForecast = $this->predictionService->forecastWithMetrics($totalHistory);
        }

        // Get top 5 trending products
        $topProducts = [];
        $allProducts = $produkModel->findAll();
        
        foreach (array_slice($allProducts, 0, 10) as $product) {
            $history = $model->getHistorisByProduk((int) $product['id']);
            if (count($history) >= 3) {
                $forecast = $this->predictionService->forecastWithMetrics($history);
                if ($forecast['valid']) {
                    $topProducts[] = [
                        'product_id' => (int) $product['id'],
                        'product_name' => (string) $product['name'],
                        'next_forecast' => round($forecast['next_prediction'], 2),
                        'id' => (int) $product['id'],
                        'name' => (string) $product['name'],
                        'forecast' => round($forecast['next_prediction'], 2),
                        'trend' => $forecast['trend_analysis']['trend'],
                        'mape' => round($forecast['metrics']['mape'], 1),
                        'has_anomalies' => count($forecast['anomalies']) > 0,
                    ];
                }
            }
        }

        // Sort by forecast (highest first)
        usort($topProducts, fn($a, $b) => $b['forecast'] <=> $a['forecast']);
        $topProducts = array_slice($topProducts, 0, 5);

        // Get products with anomalies
        $anomalyProducts = [];
        foreach (array_slice($allProducts, 0, 15) as $product) {
            $history = $model->getHistorisByProduk((int) $product['id']);
            if (count($history) >= 3) {
                $forecast = $this->predictionService->forecastWithMetrics($history);
                if ($forecast['valid'] && count($forecast['anomalies']) > 0) {
                    $latestAnomaly = $forecast['anomalies'][array_key_last($forecast['anomalies'])] ?? null;

                    $anomalyProducts[] = [
                        'product_name' => (string) $product['name'],
                        'anomaly_type' => (string) ($latestAnomaly['type'] ?? 'Unknown'),
                        'anomaly_value' => (float) ($latestAnomaly['value'] ?? 0),
                        'id' => (int) $product['id'],
                        'name' => (string) $product['name'],
                        'anomaly_count' => count($forecast['anomalies']),
                        'latest_anomaly' => $latestAnomaly,
                    ];
                }
            }
        }

        // Get recommendations summary
        $recommendations = $this->predictionService->generateStockRecommendations();
        $criticalRecs = array_filter($recommendations, fn($r) => $r['priority'] === 'critical' && $r['to_buy'] > 0);
        $highRecs = array_filter($recommendations, fn($r) => $r['priority'] === 'high' && $r['to_buy'] > 0);

        return [
            'total_forecast' => $totalForecast,
            'top_products' => $topProducts,
            'anomaly_products' => $anomalyProducts,
            'critical_recommendations' => count($criticalRecs),
            'high_recommendations' => count($highRecs),
            'total_recommendations' => count(array_filter($recommendations, fn($r) => $r['to_buy'] > 0)),
        ];
    }

    /**
     * Dashboard view with multi-level forecasts
     */
    public function dashboard()
    {
        $dashboardData = $this->predictionService->generateDashboardData();
        $recommendations = $this->predictionService->generateStockRecommendations();

        return view('prediksi/dashboard', [
            'title' => 'Dashboard Prediksi Penjualan',
            'username' => (string) session()->get('username'),
            'level' => (string) session()->get('level'),
            'activeMenu' => 'prediksi',
            'dashboard' => $dashboardData,
            'recommendations' => $recommendations,
        ]);
    }

    /**
     * Guide page for users
     */
    public function guide()
    {
        return view('prediksi/guide', [
            'title' => 'Panduan Prediksi Penjualan',
            'username' => (string) session()->get('username'),
            'level' => (string) session()->get('level'),
            'activeMenu' => 'prediksi',
        ]);
    }
}

