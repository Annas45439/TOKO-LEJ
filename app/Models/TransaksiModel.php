<?php

namespace App\Models;

use CodeIgniter\Model;

class TransaksiModel extends Model
{
    protected $table      = 'tb_transactions';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'invoice_no',
        'customer_id',
        'user_id',
        'total',
        'payment_method',
        'payment_amount',
        'change_amount',
        'status',
        'date',
        'created_at',
    ];

    public function getProductsForPos(): array
    {
        return $this->db->table('tb_products p')
            ->select('p.id, p.name, p.price, p.stock, p.min_stock, c.name AS category_name, u.name AS unit_name')
            ->join('tb_categories c', 'c.id = p.category_id', 'left')
            ->join('tb_units u', 'u.id = p.unit_id', 'left')
            ->orderBy('p.name', 'ASC')
            ->get()
            ->getResultArray();
    }

    public function getCustomers(): array
    {
        return $this->db->table('tb_customers')
            ->select('id, name')
            ->orderBy('name', 'ASC')
            ->get()
            ->getResultArray();
    }

    public function getHistory(?string $date = null): array
    {
        $builder = $this->db->table('tb_transactions t')
            ->select('t.*, c.name AS customer_name, u.username AS cashier_name')
            ->join('tb_customers c', 'c.id = t.customer_id', 'left')
            ->join('tb_users u', 'u.id = t.user_id', 'left')
            ->orderBy('t.id', 'DESC');

        if ($date !== null && $date !== '') {
            $builder->where('t.date', $date);
        }

        return $builder->get()->getResultArray();
    }
}