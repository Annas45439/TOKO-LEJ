<?php

namespace App\Models;

use CodeIgniter\Model;

class SuplierModel extends Model
{
    protected $table      = 'tb_suppliers';
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
