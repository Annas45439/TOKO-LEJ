<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ProdukSeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');

        $this->seedCategories($now);
        $this->seedUnits($now);
        $this->seedProducts($now);
    }

    private function seedCategories(string $now): void
    {
        $categories = ['Minuman', 'Makanan', 'Snack', 'Kebutuhan Harian'];

        foreach ($categories as $category) {
            $exists = $this->db->table('tb_categories')->where('name', $category)->countAllResults();
            if ($exists > 0) {
                continue;
            }

            $this->db->table('tb_categories')->insert([
                'name'       => $category,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    private function seedUnits(string $now): void
    {
        $units = ['Pcs', 'Box', 'Pack', 'Botol'];

        foreach ($units as $unit) {
            $exists = $this->db->table('tb_units')->where('name', $unit)->countAllResults();
            if ($exists > 0) {
                continue;
            }

            $this->db->table('tb_units')->insert([
                'name'       => $unit,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    private function seedProducts(string $now): void
    {
        $minumanId = $this->getIdByName('tb_categories', 'Minuman');
        $makananId = $this->getIdByName('tb_categories', 'Makanan');
        $pcsId     = $this->getIdByName('tb_units', 'Pcs');
        $botolId   = $this->getIdByName('tb_units', 'Botol');

        $products = [
            [
                'name'        => 'Air Mineral 600ml',
                'category_id' => $minumanId,
                'unit_id'     => $botolId,
                'price'       => 5000,
                'buy_price'   => 3500,
                'stock'       => 100,
                'min_stock'   => 20,
                'description' => 'Air mineral siap jual.',
            ],
            [
                'name'        => 'Mi Instan Goreng',
                'category_id' => $makananId,
                'unit_id'     => $pcsId,
                'price'       => 4000,
                'buy_price'   => 3000,
                'stock'       => 120,
                'min_stock'   => 30,
                'description' => 'Produk makanan cepat saji.',
            ],
        ];

        foreach ($products as $product) {
            if (empty($product['category_id']) || empty($product['unit_id'])) {
                continue;
            }

            $exists = $this->db->table('tb_products')->where('name', $product['name'])->countAllResults();
            if ($exists > 0) {
                continue;
            }

            $this->db->table('tb_products')->insert([
                ...$product,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    private function getIdByName(string $table, string $name): ?int
    {
        $row = $this->db->table($table)->select('id')->where('name', $name)->get()->getRowArray();

        return $row ? (int) $row['id'] : null;
    }
}
