<?php

namespace App\Controllers;

use App\Models\MMain;
use Config\Database;

class Main extends BaseController
{
    private MMain $mMain;

    public function __construct()
    {
        $this->mMain = new MMain();
    }

    public function index()
    {
        $agent = $this->request->getUserAgent();
        if (!$agent->isMobile() && (int) ($this->request->getGet('force') ?? 0) !== 1) {
            return view('restricted');
        }

        $kitab = (string) ($this->request->getGet('kitab') ?? '');
        if ($kitab === '') {
            return view('restricted');
        }

        $data = [
            'versidb' => $this->mMain->cek_update_db($kitab),
            'kitab'   => $kitab,
            'title'   => $this->mMain->kitab_get_detil($kitab, 'kitab_full'),
        ];

        return view('main', $data);
    }

    public function get_table_size(string $tbl)
    {
        return $this->response->setBody((string) $this->mMain->get_table_size($tbl));
    }

    public function getEmptyPage(string $tbl)
    {
        return $this->response->setBody((string) $this->mMain->getEmptyPage($tbl));
    }

    public function cari(string $tbl)
    {
        $re = (string) ($this->request->getGet('re') ?? '');

        return $this->response->setJSON($this->mMain->cari($tbl, $re));
    }

    public function baca(string $tbl, int $id)
    {
        return $this->response->setJSON($this->mMain->baca($tbl, $id));
    }

    public function get_ids(string $tbl)
    {
        return $this->response->setJSON($this->mMain->get_ids($tbl));
    }

    public function get_toc(string $tbl)
    {
        return $this->response->setJSON($this->mMain->get_toc($tbl . '_toc'));
    }

    public function get_kitab(string $tbl)
    {
        return $this->response->setJSON($this->mMain->get_kitab($tbl));
    }

    public function cek_update_js(string $tbl)
    {
        $newVersi = $this->mMain->cek_update_db($tbl);
        $js = 'if((typeof localforage !== "undefined")||(typeof dbManager.add === "undefined")){showAlert("Aplikasi kadaluarsa. Silakan update via PlayStore!")}'
            . 'else{'
            . 'let newVersi="' . $newVersi . '", oldVersiDb = getCurVersiDb();'
            . 'if(newVersi > oldVersiDb){'
            . 'if(oldVersiDb=="1990-01-01"){showAlert("Memuat data dari server");$("#div-terjemah").html("<h3>Mohon tunggu sebentar... Sedang mengambil data dari server.</h3>Jangan tutup aplikasi hingga selesai. Proses ini mungkin butuh waktu lama, tergantung koneksi jaringan internet anda.")}else{showAlert("Meng-update database...")}'
            . 'let s2 = document.createElement("script");'
            . 's2.src = "https://terjemah.tohakepriben.com/main/get_kitab_js/' . $tbl . '/"+oldVersiDb;'
            . 'document.body.appendChild(s2);'
            . 'if(typeof tryInit == "function") tryInit();'
            . '}'
            . '}';

        return $this->response->setBody($js);
    }

    public function get_kitab_js(string $tbl, string $oldVersi = '1990-01-01')
    {
        $db = Database::connect();
        $newVersi = $this->mMain->cek_update_db($tbl);
        $where = ['updated >' => $oldVersi];

        $kitab = $db->table($tbl)
            ->select('id,nash,terjemah')
            ->where($where)
            ->get()
            ->getResultArray();

        $out = 'let newKitab=[';
        foreach ($kitab as $d) {
            $out .= '{id:' . $d['id'] . ',nash:`' . $d['nash'] . '`,terjemah:`' . $d['terjemah'] . '`},';
        }
        $out .= '];';

        $adaPerubahanToc = $db->table($tbl . '_toc')->where($where)->countAllResults() > 0;
        if ($adaPerubahanToc) {
            $toc = $db->table($tbl . '_toc')->get()->getResultArray();
            foreach ($toc as &$item) {
                if ((int) $item['parent'] === 0) {
                    $item['parent'] = '#';
                }
            }
            unset($item);

            $out .= 'let newTOC=[';
            foreach ($toc as $d) {
                $out .= '{id:' . $d['id'] . ',parent:"' . $d['parent'] . '",text:`' . $d['text'] . '`,terjemah:`' . $d['terjemah'] . '`},';
            }
            $out .= '];';
        }

        $out .= 'async function updateNewKitab(){';
        $out .= 'try{for(const data of newKitab){';
        $out .= 'await dbManager.addOrUpdate(data)';
        $out .= '}';

        if ($adaPerubahanToc) {
            $out .= 'lsSet("toc", JSON.stringify(newTOC));try{$("#toc").jstree(true).destroy();}catch(er){console.log(er)}';
        }

        $out .= 'lsSet("curVersiDb","' . $newVersi . '");';
        $out .= 'initApp();';
        $out .= 'showAlert("Update data berhasil.");';
        $out .= 'if(typeof tryInit == "function") clearTimeout(timeOutTryInit);';
        $out .= '} catch(e){console.log(e)}}';
        $out .= 'updateNewKitab();';

        return $this->response->setBody($out);
    }

    public function cek_update_db(string $tbl)
    {
        return $this->response->setBody((string) $this->mMain->cek_update_db($tbl));
    }

    public function charcode()
    {
        $ch = ['ا', 'أ', 'إ', 'آ', 'ّ', 'َ', 'ً', 'ُ', 'ٌ', 'ِ', 'ٍ', 'ْ', 'ٓ', 'ٰ'];

        return $this->response->setJSON($ch);
    }

    public function coba()
    {
        $str = '<code><p>mhfyu uyfjguk ilgiuy</p><p>mhfyu uyfjguk ilgiuy2222</p></code>';

        return $this->response->setBody(replace_p($str));
    }
}
