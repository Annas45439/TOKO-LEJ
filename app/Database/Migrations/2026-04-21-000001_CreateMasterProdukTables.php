<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMasterProdukTables extends Migration
{
    public function up()
    {
        $this->createCategoriesTable();
        $this->createUnitsTable();
        $this->createProductsTable();
    }

    public function down()
    {
        $this->forge->dropTable('tb_products', true);
        $this->forge->dropTable('tb_units', true);
        $this->forge->dropTable('tb_categories', true);
    }

    private function createCategoriesTable(): void
    {
        if ($this->db->tableExists('tb_categories')) {
            return;
        }

        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
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
        $this->forge->addUniqueKey('name', 'uq_tb_categories_name');
        $this->forge->createTable('tb_categories', true);
    }

    private function createUnitsTable(): void
    {
        if ($this->db->tableExists('tb_units')) {
            return;
        }

        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
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
        $this->forge->addUniqueKey('name', 'uq_tb_units_name');
        $this->forge->createTable('tb_units', true);
    }

    private function createProductsTable(): void
    {
        if ($this->db->tableExists('tb_products')) {
            return;
        }

        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'category_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'unit_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => 120,
            ],
            'price' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
                'default'    => 0,
            ],
            'buy_price' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
                'default'    => 0,
            ],
            'stock' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            'min_stock' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
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
        $this->forge->addUniqueKey('name', 'uq_tb_products_name');
        $this->forge->addKey('category_id');
        $this->forge->addKey('unit_id');
        $this->forge->addForeignKey('category_id', 'tb_categories', 'id', 'RESTRICT', 'RESTRICT');
        $this->forge->addForeignKey('unit_id', 'tb_units', 'id', 'RESTRICT', 'RESTRICT');
        $this->forge->createTable('tb_products', true);
    }
}
