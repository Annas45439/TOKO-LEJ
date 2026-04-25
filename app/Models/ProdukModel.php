<?php

namespace App\Models;

use CodeIgniter\Model;

class ProdukModel extends Model
{
    protected $table            = 'tb_products';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $useAutoIncrement = true;
    protected $useTimestamps    = true;
    protected $allowedFields    = [
        'category_id',
        'unit_id',
        'name',
        'price',
        'buy_price',
        'stock',
        'min_stock',
        'description',
    ];

    public function getAllWithRelation(?string $search = null, ?int $categoryId = null): array
    {
        $builder = $this->db->table($this->table . ' p')
            ->select('p.*, c.name AS category_name, u.name AS unit_name')
            ->join('tb_categories c', 'c.id = p.category_id', 'left')
            ->join('tb_units u', 'u.id = p.unit_id', 'left')
            ->orderBy('p.id', 'DESC');

        if ($search !== null && $search !== '') {
            $builder->groupStart()
                ->like('p.name', $search)
                ->orLike('c.name', $search)
                ->groupEnd();
        }

        if ($categoryId !== null && $categoryId > 0) {
            $builder->where('p.category_id', $categoryId);
        }

        return $builder->get()->getResultArray();
    }

    public function getStokHampirHabis(): array
    {
        return $this->where('stock <= min_stock')->findAll();
    }
}