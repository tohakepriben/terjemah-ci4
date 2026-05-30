<?php

namespace App\Models;

use CodeIgniter\Model;

class MHadits extends Model
{
    public function get_data(string $nd): array
    {
        return $this->db->table($nd)
            ->select('id, CONCAT("<div>",nash,"</div><div>",terjemah,"</div>") as nd', false)
            ->get()
            ->getResultArray();
    }

    public function get_toc(string $nd): array
    {
        return $this->db->table($nd . '_toc')
            ->select('id, cek_root(parent) as parent, CONCAT(text,"<label>",id,"</label>") as text', false)
            ->orderBy('id', 'ASC')
            ->get()
            ->getResultArray();
    }
}
