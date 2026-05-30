<?php

namespace App\Models;

use CodeIgniter\Model;

class MUser extends Model
{
    public function login(string $email, string $password): bool
    {
        return $this->db->table('user')
            ->where(['email' => $email, 'password' => $password])
            ->countAllResults() > 0;
    }
}
