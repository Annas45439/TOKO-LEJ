<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSalesMonthlyTable extends Migration
{
    public function up()
    {
        // Create tb_sales_monthly table for storing aggregated monthly sales data
        if ($this->db->tableExists('tb_sales_monthly')) {
            return;
        }

        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'product_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
            ],
            'category_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'year' => [
                'type'       => 'INT',
                'constraint' => 4,
                'null'       => false,
            ],
            'month' => [
                'type'       => 'INT',
                'constraint' => 2,
                'null'       => false,
            ],
            'qty' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'default'    => 0,
            ],
            'total_sales' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
                'default'    => 0,
            ],
            'period' => [
                'type'       => 'VARCHAR',
                'constraint' => 10,
                'null'       => true,
                'comment'    => 'Format: MM/YYYY',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('product_id');
        $this->forge->addKey('category_id');
        $this->forge->addKey(['year', 'month']);
        $this->forge->addUniqueKey(['product_id', 'year', 'month']);

        $this->forge->createTable('tb_sales_monthly', true);
    }

    public function down()
    {
        $this->forge->dropTable('tb_sales_monthly', true);
    }
}
