<?php

namespace App\Controllers;

use Config\Database;

class Kmtq extends BaseController
{
    public function index()
    {
        return view('lz_string');
    }

    public function get_arid()
    {
        $db = Database::connect();
        $data = $db->table('arab_indo_final')->get()->getResultArray();
        $i = 0;
        $out = 'var dbArId = [<br>';

        foreach ($data as $r) {
            $i++;
            $out .= "'" . str_replace("'", "\\'", $r['kamus']) . "',";
            if ($i > 500) {
                $i = 0;
                $out .= '<br>';
            } else {
                $i++;
            }
        }

        $out .= ']';

        return $this->response->setBody($out);
    }

    public function get_arar()
    {
        $db = Database::connect();
        $data = $db->table('arab_arab_final')->get()->getResultArray();
        $i = 0;
        $out = 'var dbArAr = [<br>';

        foreach ($data as $r) {
            $i++;
            $out .= "'" . str_replace("'", "\\'", $r['kamus']) . "',";
            if ($i > 100) {
                $i = 0;
                $out .= '<br>';
            } else {
                $i++;
            }
        }

        $out .= ']';

        return $this->response->setBody($out);
    }

    public function get_tjalal()
    {
        $db = Database::connect();
        $data = $db->table('tafsir_jalalain')->select('id,nash')->get()->getResultArray();
        $i = 0;
        $out = 'var dbJalalain = [';

        foreach ($data as $r) {
            $i++;
            $out .= "'" . str_replace("'", "\\'", $r['nash']) . "',";
            if ($i > 100) {
                $i = 0;
                $out .= '<br>';
            } else {
                $i++;
            }
        }

        $out .= '];';

        return $this->response->setBody($out);
    }
}
