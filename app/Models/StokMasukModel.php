<?php

namespace App\Models;

use CodeIgniter\Model;

class StokMasukModel extends Model
{
    protected $table      = 'tb_stock_in';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'product_id',
        'supplier_id',
        'user_id',
        'qty',
        'buy_price',
        'total_price',
        'date',
        'notes',
        'created_at',
    ];

    public function getHistory(?string $date = null): array
    {
        $builder = $this->db->table('tb_stock_in s')
            ->select('s.*, p.name AS product_name, sp.name AS supplier_name, u.username AS username')
            ->join('tb_products p', 'p.id = s.product_id', 'left')
            ->join('tb_suppliers sp', 'sp.id = s.supplier_id', 'left')
            ->join('tb_users u', 'u.id = s.user_id', 'left')
            ->orderBy('s.id', 'DESC');

        if ($date !== null && $date !== '') {
            $builder->where('s.date', $date);
        }

        return $builder->get()->getResultArray();
    }
}