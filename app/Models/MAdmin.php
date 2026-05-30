<?php

namespace App\Models;

use CodeIgniter\Model;

class MAdmin extends Model
{
    public function get_max_id(string $kitab)
    {
        return $this->db->table($kitab)
            ->select('max(id) as max')
            ->get()
            ->getRow('max');
    }

    public function add(string $kitab, array $data): bool
    {
        return $this->db->table($kitab)->insert($data);
    }

    public function update_data(string $kitab, array $data, $id, string $col = 'id'): bool
    {
        return $this->db->table($kitab)->update($data, [$col => $id]);
    }

    public function add_page(string $kitab, int $after_pg): bool
    {
        $sql1 = 'update ' . $kitab . ' set id = id + 1 where id > ' . $after_pg . ' order by id desc';
        $sql2 = 'update ' . $kitab . '_toc set id = id + 1 where id > ' . $after_pg . ' order by id desc';
        $sql3 = 'update ' . $kitab . '_toc set parent = parent + 1 where parent > ' . $after_pg . ' order by parent desc';
        $ret = $this->db->query($sql1) && $this->db->query($sql2) && $this->db->query($sql3);

        return $ret && $this->add($kitab, ['id' => $after_pg + 1, 'nash' => '']);
    }

    public function rem_page(string $kitab, int $pg): bool
    {
        if ($this->db->table($kitab)->delete(['id' => $pg])) {
            $sql1 = 'update ' . $kitab . ' set id = id - 1 where id > ' . $pg . ' order by id asc';
            $sql2 = 'update ' . $kitab . '_toc set id = id - 1 where id > ' . $pg . ' order by id asc';
            $sql3 = 'update ' . $kitab . '_toc set parent = parent - 1 where parent > ' . $pg . ' order by parent asc';

            return $this->db->query($sql1) && $this->db->query($sql2) && $this->db->query($sql3);
        }

        return false;
    }

    public function rem_page2(string $kitab, int $pg): bool
    {
        $sql2 = 'update ' . $kitab . '_toc set id = id - 1 where id > ' . $pg . ' order by id asc';
        $sql3 = 'update ' . $kitab . '_toc set parent = parent - 1 where parent > ' . $pg . ' order by parent asc';

        return $this->db->query($sql2) && $this->db->query($sql3);
    }

    public function add_toc(string $kitab, array $data): bool
    {
        return $this->db->table($kitab . '_toc')->insert($data);
    }

    public function update_toc(string $kitab, int $id, array $data): bool
    {
        return $this->db->table($kitab . '_toc')->update($data, ['id' => $id]);
    }

    public function rem_toc_child(string $kitab): void
    {
        $sqlCnt = 'select count(id) as cnt from ' . $kitab . '_toc where parent>0 and parent not in(select id from ' . $kitab . '_toc)';

        if ((int) $this->db->query($sqlCnt)->getRow('cnt') > 0) {
            $sqlDlt = 'delete from ' . $kitab . '_toc where parent>0 and parent not in(select id from ' . $kitab . '_toc)';
            $this->db->query($sqlDlt);
            $this->rem_toc_child($kitab);
        }
    }

    public function rem_toc(string $kitab, int $id): int
    {
        $this->db->table($kitab . '_toc')->delete(['id' => $id]);
        $this->rem_toc_child($kitab);

        return 1;
    }

    public function update_db_server(string $kitab): string
    {
        $curdate = date('Y-m-d');
        $this->db->table('terjemah_index')->update(['versi' => $curdate], ['kitab' => $kitab]);

        return $curdate;
    }

    public function kitab_matc_password(string $kitab, string $password): bool
    {
        return $this->db->table('terjemah_index')
            ->where(['kitab' => $kitab, 'password' => $password])
            ->countAllResults() > 0;
    }

    public function kitab_get_all(): array
    {
        return $this->db->table('terjemah_index')
            ->orderBy('kitab')
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
