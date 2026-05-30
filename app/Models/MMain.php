<?php

namespace App\Models;

use CodeIgniter\Model;

class MMain extends Model
{
    public function get_table_size(string $tbl)
    {
        return $this->db->table('information_schema.TABLES')
            ->select('(data_length + index_length) AS size', false)
            ->where('table_name', $tbl)
            ->where('table_schema', $this->db->getDatabase())
            ->get()
            ->getRow('size');
    }

    public function cek_update_db(string $tbl)
    {
        return $this->db->table('terjemah_index')
            ->where('kitab', $tbl)
            ->get()
            ->getRow('versi');
    }

    public function getEmptyPage(string $tbl)
    {
        $sql = 'select id from ' . $tbl . " where nash is NULL or nash = '' or terjemah is NULL or terjemah = '' limit 1";

        return $this->db->query($sql)->getRow('id');
    }

    public function cari(string $tbl, string $re): array
    {
        $ret = [];
        $data = $this->db->table($tbl)->get()->getResultArray();

        foreach ($data as $r) {
            $nash = replace_hamzah(rem_harokat(replace_p($r['nash'] ?? '')));
            $terjemah = replace_p($r['terjemah'] ?? '');

            if (@preg_match($re, $nash . ' ' . $terjemah)) {
                $ret[] = $r['id'];
            }
        }

        return $ret;
    }

    public function get_ids(string $tbl): array
    {
        return $this->db->table($tbl)
            ->select('id')
            ->get()
            ->getResultArray();
    }

    public function baca(string $tbl, int $id): array
    {
        return $this->db->table($tbl)
            ->where('id', $id)
            ->get()
            ->getResultArray();
    }

    public function get_toc(string $tbl): array
    {
        return $this->db->table($tbl)
            ->select('id, if(parent=0, "#", parent) as parent, CONCAT(text,"<label>",id,"</label>","<p>",terjemah,"</p>") as text', false)
            ->orderBy('id', 'ASC')
            ->get()
            ->getResultArray();
    }

    public function get_kitab(string $tbl): array
    {
        return $this->db->table($tbl)
            ->orderBy('id')
            ->get()
            ->getResultArray();
    }

    public function kitab_get_detil(string $kitab, string $row)
    {
        return $this->db->table('terjemah_index')
            ->where('kitab', $kitab)
            ->get()
            ->getRow($row);
    }
}
