<?php

namespace App\Models;

use CodeIgniter\Model;

class MCh extends Model
{
    public function insert_data(string $tbl, array $data): bool
    {
        return $this->db->table('ch_' . $tbl)->insert($data);
    }
}
