<?php

namespace App\Controllers;

use App\Models\MAdmin;
use App\Models\MMain;
use Config\Database;

class Admin123 extends BaseController
{
    private MAdmin $mAdmin;
    private MMain $mMain;

    public function __construct()
    {
        $this->mAdmin = new MAdmin();
        $this->mMain = new MMain();
    }

    public function index()
    {
        if ($this->session->has('kitab')) {
            $kitab = (string) $this->session->get('kitab');
            $data = [
                'versidb' => $this->mMain->cek_update_db($kitab),
                'kitab'   => $kitab,
                'title'   => $this->mAdmin->kitab_get_detil($kitab, 'kitab_full'),
            ];

            return view('admin', $data);
        }

        $kitab = (string) $this->request->getPost('kitab');
        $pwd = (string) $this->request->getPost('pwd');

        if ($this->mAdmin->kitab_matc_password($kitab, $pwd)) {
            $this->session->set('kitab', $kitab);
            $data = [
                'versidb' => $this->mMain->cek_update_db($kitab),
                'kitab'   => $kitab,
                'title'   => $this->mAdmin->kitab_get_detil($kitab, 'kitab_full'),
            ];

            return view('admin', $data);
        }

        return view('login_admin', ['arr_kitab' => $this->mAdmin->kitab_get_all()]);
    }

    public function logout()
    {
        $this->session->destroy();

        return redirect()->to(base_url('admin123'));
    }

    public function get_max_id(string $kitab)
    {
        if (!$this->session->has('kitab')) {
            return;
        }

        return $this->response->setBody((string) $this->mAdmin->get_max_id($kitab));
    }

    public function add(string $tbl)
    {
        if (!$this->session->has('kitab')) {
            return;
        }

        $nash = replace_p((string) $this->request->getPost('nash'));
        $noHarokat = replace_hamzah(rem_harokat($nash));
        $terjemah = replace_p((string) $this->request->getPost('terjemah'));

        $data = [
            'nash'      => $nash,
            'no_harokat' => $noHarokat,
            'terjemah'  => $terjemah,
        ];

        return $this->response->setBody((string) (int) $this->mAdmin->add($tbl, $data));
    }

    public function update_nash(int $id)
    {
        if (!$this->session->has('kitab')) {
            return;
        }

        $kitab = (string) $this->request->getPost('kitab');
        $nash = replace_p((string) $this->request->getPost('nash'));
        $noHarokat = replace_hamzah(rem_harokat($nash));
        $terjemah = replace_brnbsp(replace_p2((string) $this->request->getPost('terjemah')));

        $data = [
            'nash'      => $nash,
            'noharokat' => $noHarokat,
            'terjemah'  => $terjemah,
        ];

        return $this->response->setBody((string) (int) $this->mAdmin->update_data($kitab, $data, $id));
    }

    public function update_noharokat_batch(int $awal = 0, int $akhir = 0)
    {
        if (!$this->session->has('kitab')) {
            return;
        }

        $kitab = (string) $this->session->get('kitab');
        if ($awal <= 0) {
            return;
        }

        $db = Database::connect();
        $arr = $db->table($kitab)
            ->where('id >=', $awal)
            ->where('id <=', $akhir)
            ->get()
            ->getResultArray();

        $out = '';
        foreach ($arr as $r) {
            $noHarokat = replace_hamzah(rem_harokat($r['nash']));
            $out .= (int) $this->mAdmin->update_data($kitab, ['noharokat' => $noHarokat], $r['id']) . '-';
        }

        return $this->response->setBody($out);
    }

    public function getEmptyPage(string $kitab)
    {
        $db = Database::connect();
        $sql = "select id from {$kitab} where nash is NULL or nash = '' or terjemah is NULL or terjemah = '' limit 1";

        return $this->response->setBody((string) $db->query($sql)->getRow('id'));
    }

    public function add_page(string $kitab, int $after_pg)
    {
        if (!$this->session->has('kitab')) {
            return;
        }

        return $this->response->setBody((string) (int) $this->mAdmin->add_page($kitab, $after_pg));
    }

    public function rem_page(string $kitab, int $pg)
    {
        if (!$this->session->has('kitab')) {
            return;
        }

        return $this->response->setBody((string) (int) $this->mAdmin->rem_page($kitab, $pg));
    }

    public function rem_page2(string $kitab, int $pg)
    {
        if (!$this->session->has('kitab')) {
            return;
        }

        return $this->response->setBody((string) (int) $this->mAdmin->rem_page2($kitab, $pg));
    }

    public function add_toc(string $kitab)
    {
        if (!$this->session->has('kitab')) {
            return;
        }

        $noHarokat = rem_harokat((string) $this->request->getPost('text'));
        $data = [
            'id'       => $this->request->getPost('id'),
            'parent'   => $this->request->getPost('parent'),
            'text'     => $noHarokat,
            'terjemah' => $this->request->getPost('terjemah'),
        ];

        return $this->response->setBody((string) (int) $this->mAdmin->add_toc($kitab, $data));
    }

    public function update_toc(string $kitab, int $id)
    {
        if (!$this->session->has('kitab')) {
            return;
        }

        $noHarokat = rem_harokat((string) $this->request->getPost('text'));
        $data = [
            'text'     => $noHarokat,
            'terjemah' => $this->request->getPost('terjemah'),
        ];

        return $this->response->setBody((string) (int) $this->mAdmin->update_toc($kitab, $id, $data));
    }

    public function rem_toc(string $kitab, int $id)
    {
        if (!$this->session->has('kitab')) {
            return;
        }

        return $this->response->setBody((string) $this->mAdmin->rem_toc($kitab, $id));
    }

    public function update_all_toc(string $kitab)
    {
        if (!$this->session->has('kitab')) {
            return;
        }

        $db = Database::connect();
        $arr = $db->table($kitab . '_toc')->get()->getResultArray();
        $out = '';

        foreach ($arr as $t) {
            $data = ['text' => rem_harokat($t['text'])];
            $out .= (int) $this->mAdmin->update_toc($kitab, (int) $t['id'], $data) . '<br/>';
        }

        return $this->response->setBody($out);
    }

    public function update_db_server(string $kitab)
    {
        if (!$this->session->has('kitab')) {
            return;
        }

        return $this->response->setBody($this->mAdmin->update_db_server($kitab));
    }

    public function matan_biru()
    {
        $kitab = (string) sesi('kitab');
        $db = Database::connect();
        $data = $db->table($kitab)->notLike('nash', '%color%')->get()->getResultArray();
        $out = '';

        foreach ($data as $r) {
            $mb = matan_biru2($r['nash']);
            $arr = ['nash' => $mb];
            $out .= $r['id'] . ' => ' . (int) $this->mAdmin->update_data($kitab, $arr, $r['id']) . '<br>';
        }

        return $this->response->setBody($out);
    }

    public function nasafiRemAyat(int $start, int $end)
    {
        $db = Database::connect();
        $data = $db->table('tafsir_nasafi')
            ->select('id, nash')
            ->where('id >=', $start)
            ->where('id <=', $end)
            ->get()
            ->getResultArray();

        $out = '';
        foreach ($data as $d) {
            $nash = preg_replace('/^[^>]*>\s*/', '', $d['nash']) ?? $d['nash'];
            $noHarokat = replace_hamzah(rem_harokat(replace_p($nash)));
            $upd = [
                'nash'      => $nash,
                'noharokat' => $noHarokat,
            ];
            if ($this->mAdmin->update_data('tafsir_nasafi', $upd, $d['id'])) {
                $out .= $d['id'] . ' Sukses<br>';
            } else {
                $out .= $d['id'] . ' Gagal<br>';
            }
        }

        return $this->response->setBody($out);
    }

    public function gantiPetikDenganKurung()
    {
        if (!$this->session->has('kitab')) {
            return;
        }

        $db = Database::connect();
        $kitab = (string) sesi('kitab');
        $data = $db->table($kitab)->get()->getResultArray();
        $out = '';

        foreach ($data as $d) {
            $hasil = preg_replace('/"([^"]*)"/', '«$1»', $d['nash']) ?? $d['nash'];
            $arr = ['nash' => $hasil];
            $out .= $d['id'] . ' => ' . (int) $this->mAdmin->update_data($kitab, $arr, $d['id']) . '<br>';
        }

        return $this->response->setBody($out);
    }

    public function updateDB(string $kitab, string $curdate)
    {
        $db = Database::connect();

        return $this->response->setBody((string) (int) $db->table('terjemah_index')->update(['versi' => $curdate], ['kitab' => $kitab]));
    }

    public function top_words()
    {
        if (!$this->session->has('kitab')) {
            return;
        }

        $db = Database::connect();
        $data = $db->table((string) sesi('kitab'))->where('id >', 612)->get()->getResult();
        $allText = '';

        foreach ($data as $row) {
            $allText .= ' ' . $row->nash;
        }

        $allText = preg_replace('/[^\p{L}\p{N}\s]/u', ' ', $allText) ?? $allText;
        $words = preg_split('/\s+/', trim($allText)) ?: [];
        $counts = array_count_values($words);
        arsort($counts);
        $topWords = array_slice($counts, 0, 500, true);

        $out = "<table border='1' cellpadding='5' cellspacing='0'>";
        $out .= '<tr><th>Kata</th><th>Jumlah Muncul</th></tr>';
        foreach ($topWords as $kata => $jumlah) {
            $out .= '<tr>';
            $out .= '<td> ' . htmlentities((string) $kata) . ' </td>';
            $out .= '<td align="center">' . $jumlah . '</td>';
            $out .= '</tr>';
        }
        $out .= '</table>';

        return $this->response->setBody($out);
    }

    public function tambah_harakat()
    {
        $filePath = FCPATH . 'teks_map.txt';
        $mapping = [];

        if (is_file($filePath)) {
            $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                if (strpos($line, '=') !== false) {
                    [$asli, $harakat] = explode('=', $line, 2);
                    $mapping[trim($asli)] = trim($harakat);
                }
            }
        } else {
            return $this->response->setBody('File mapping tidak ditemukan!');
        }

        $db = Database::connect();
        $nashList = $db->table((string) sesi('kitab'))->where('id >', 612)->get()->getResult();

        foreach ($nashList as $row) {
            $text = $row->nash;
            foreach ($mapping as $tanpa => $berharakat) {
                $pattern = '/(?<=^|[\s،؛,.؟!])' . preg_quote($tanpa, '/') . '(?=$|[\s،؛,.؟!])/u';
                $text = preg_replace($pattern, $berharakat, $text) ?? $text;
            }

            $db->table((string) sesi('kitab'))
                ->where('id', $row->id)
                ->update(['nash' => $text]);
        }

        return $this->response->setBody('Proses penambahan harakat selesai.');
    }
}
