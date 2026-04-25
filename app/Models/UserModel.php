<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table      = 'tb_users';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'username',
        'password',
        'level',
        'created_at',
        'updated_at',
    ];

    public function getUserByUsername(string $username): ?array
    {
        return $this->where('username', $username)->first();
    }
}