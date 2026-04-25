<?php

namespace App\Controllers;

use Config\Database;

class Dashboard extends BaseController
{
    public function index()
    {
        if (session()->get('logged_in') !== true) {
            return redirect()->to('/')->with('error', 'Silakan login terlebih dahulu.');
        }

        $level = session()->get('level');

        if ($level === 'admin') {
            return redirect()->to('/dashboard/admin');
        }

        if ($level === 'kasir') {
            return redirect()->to('/dashboard/kasir');
        }

        return redirect()->to('/')->with('error', 'Level pengguna tidak dikenali.');
    }

    public function admin()
    {
        if (session()->get('logged_in') !== true) {
            return redirect()->to('/')->with('error', 'Silakan login terlebih dahulu.');
        }

        if (session()->get('level') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak. Halaman admin saja.');
        }

        $stats = $this->getDashboardStats();

        return view('dashboard/admin', [
            'title'    => 'Dashboard Admin',
            'username' => (string) session()->get('username'),
            'level'    => (string) session()->get('level'),
            'activeMenu' => 'dashboard',
            'stats'    => $stats,
        ]);
    }

    public function kasir()
    {
        if (session()->get('logged_in') !== true) {
            return redirect()->to('/')->with('error', 'Silakan login terlebih dahulu.');
        }

        if (session()->get('level') !== 'kasir') {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak. Halaman kasir saja.');
        }

        $stats = $this->getDashboardStats();

        return view('dashboard/kasir', [
            'title'    => 'Dashboard Kasir',
            'username' => (string) session()->get('username'),
            'level'    => (string) session()->get('level'),
            'activeMenu' => 'dashboard',
            'stats'    => $stats,
        ]);
    }

    private function getDashboardStats(): array
    {
        $stats = [
            'products'          => 0,
            'transactionsToday' => 0,
            'lowStock'          => 0,
            'customers'         => 0,
            'monthlyLabels'     => [],
            'monthlyTotals'     => [],
            'lowStockItems'     => [],
            'recentTransactions' => [],
        ];

        try {
            $db = Database::connect();

            if ($db->tableExists('tb_products')) {
                $stats['products'] = (int) $db->table('tb_products')->countAllResults();

                $fields = $db->getFieldNames('tb_products');
                if (in_array('stock', $fields, true) && in_array('min_stock', $fields, true)) {
                    $stats['lowStock'] = (int) $db->table('tb_products')
                        ->where('stock <= min_stock')
                        ->countAllResults();

                    $stats['lowStockItems'] = $db->table('tb_products')
                        ->select('id, name, stock, min_stock')
                        ->where('stock <= min_stock')
                        ->orderBy('stock', 'ASC')
                        ->limit(5)
                        ->get()
                        ->getResultArray();
                }
            }

            if ($db->tableExists('tb_customers')) {
                $stats['customers'] = (int) $db->table('tb_customers')->countAllResults();
            }

            if ($db->tableExists('tb_transactions')) {
                $fields = $db->getFieldNames('tb_transactions');

                // Query transaksi hari ini menggunakan kolom 'date'
                $stats['transactionsToday'] = (int) $db->table('tb_transactions')
                    ->where('DATE(date)', date('Y-m-d'))
                    ->countAllResults();

                $stats = $this->appendMonthlyChartData($db, $stats, $fields);
                $stats = $this->appendRecentTransactions($db, $stats, $fields);
            }
        } catch (\Throwable $e) {
            // Keep default stats when database metric queries fail.
        }

        return $stats;
    }

    private function appendMonthlyChartData($db, array $stats, array $fields): array
    {
        $dateColumn = $this->pickTransactionDateColumn($fields);

        if ($dateColumn === null) {
            return $stats;
        }

        $labels = [];
        $totals = [];
        $map = [];

        for ($i = 5; $i >= 0; $i--) {
            $period = date('Y-m', strtotime('-' . $i . ' month'));
            $labels[] = date('M Y', strtotime($period . '-01'));
            $totals[] = 0;
            $map[$period] = count($labels) - 1;
        }

        $rows = $db->table('tb_transactions')
            ->select("DATE_FORMAT($dateColumn, '%Y-%m') AS period, COUNT(*) AS total", false)
            ->where("$dateColumn IS NOT NULL", null, false)
            ->groupBy("DATE_FORMAT($dateColumn, '%Y-%m')", false)
            ->get()
            ->getResultArray();

        foreach ($rows as $row) {
            $period = (string) ($row['period'] ?? '');

            if ($period !== '' && array_key_exists($period, $map)) {
                $totals[$map[$period]] = (int) ($row['total'] ?? 0);
            }
        }

        $stats['monthlyLabels'] = $labels;
        $stats['monthlyTotals'] = $totals;

        return $stats;
    }

    private function appendRecentTransactions($db, array $stats, array $fields): array
    {
        $dateColumn = $this->pickTransactionDateColumn($fields);
        if ($dateColumn === null) {
            return $stats;
        }

        $select = [
            'invoice_no',
            'total',
            'payment_method',
            'status',
            $dateColumn,
        ];

        $stats['recentTransactions'] = $db->table('tb_transactions')
            ->select(implode(',', $select))
            ->orderBy($dateColumn, 'DESC')
            ->limit(5)
            ->get()
            ->getResultArray();

        return $stats;
    }

    private function pickTransactionDateColumn(array $fields): ?string
    {
        if (in_array('date', $fields, true)) {
            return 'date';
        }

        if (in_array('transaction_date', $fields, true)) {
            return 'transaction_date';
        }

        return null;
    }
}