<?php

namespace App\Models;

use CodeIgniter\Model;

class PelangganModel extends Model
{
    protected $table      = 'tb_customers';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'name',
        'phone',
        'email',
        'address',
        'notes',
        'created_at',
        'updated_at',
    ];
}
