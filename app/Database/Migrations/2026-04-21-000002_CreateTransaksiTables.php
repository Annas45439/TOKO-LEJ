<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTransaksiTables extends Migration
{
    public function up()
    {
        $this->createUsersTable();
        $this->createCustomersTable();
        $this->createTransactionsTable();
        $this->createTransactionDetailsTable();
    }

    public function down()
    {
        $this->forge->dropTable('tb_transaction_details', true);
        $this->forge->dropTable('tb_transactions', true);
        $this->forge->dropTable('tb_customers', true);
        $this->forge->dropTable('tb_users', true);
    }

    private function createUsersTable(): void
    {
        if ($this->db->tableExists('tb_users')) {
            return;
        }

        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'username' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'password' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'level' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'default'    => 'user',
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
        $this->forge->addUniqueKey('username');
        $this->forge->createTable('tb_users', true);

        // Insert demo user
        $this->db->table('tb_users')->insert([
            'username' => 'admin',
            'password' => password_hash('admin123', PASSWORD_DEFAULT),
            'level' => 'admin',
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

    private function createCustomersTable(): void
    {
        if ($this->db->tableExists('tb_customers')) {
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
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('tb_customers', true);

        // Insert demo customer
        $this->db->table('tb_customers')->insert([
            'name' => 'Pelanggan Umum',
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

    private function createTransactionsTable(): void
    {
        if ($this->db->tableExists('tb_transactions')) {
            return;
        }

        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'invoice_no' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'customer_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'total' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
            ],
            'payment_method' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
            ],
            'payment_amount' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
            ],
            'change_amount' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
                'default'    => 0,
            ],
            'status' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'default'    => 'completed',
            ],
            'date' => [
                'type' => 'DATE',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('customer_id');
        $this->forge->addKey('user_id');
        $this->forge->addUniqueKey('invoice_no');
        $this->forge->createTable('tb_transactions', true);
    }

    private function createTransactionDetailsTable(): void
    {
        if ($this->db->tableExists('tb_transaction_details')) {
            return;
        }

        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'transaction_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'product_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'qty' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'price' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
            ],
            'subtotal' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('transaction_id');
        $this->forge->addKey('product_id');
        $this->forge->createTable('tb_transaction_details', true);
    }
}
?>

