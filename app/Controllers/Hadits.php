<?php

namespace App\Controllers;

use App\Models\MHadits;

class Hadits extends BaseController
{
    private MHadits $mHadits;

    public function __construct()
    {
        $this->mHadits = new MHadits();
    }

    public function index()
    {
        $tables = 'hadits_bukhori,hadits_muslim,hadits_nasai,hadits_abu_dawud,hadits_tirmidzi,hadits_ibnu_majah,hadits_muwatho,hadits_syafii,hadits_ahmad,hadits_darimi,hadits_riyadlus_sholihin';

        return view('hadits', ['tables' => $tables]);
    }

    public function get(string $nd)
    {
        return $this->response->setJSON($this->mHadits->get_data($nd));
    }

    public function get_toc(string $nd)
    {
        return $this->response->setJSON($this->mHadits->get_toc($nd));
    }
}
