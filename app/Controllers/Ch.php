<?php

namespace App\Controllers;

use App\Models\MCh;

class Ch extends BaseController
{
    private MCh $mCh;

    public function __construct()
    {
        $this->mCh = new MCh();
    }

    public function index()
    {
        return view('ch');
    }

    public function insert(string $tbl)
    {
        $data = [
            'id'       => $this->request->getPost('id'),
            'nass'     => $this->request->getPost('nass'),
            'terjemah' => $this->request->getPost('terjemah'),
        ];

        return $this->response->setBody((string) (int) $this->mCh->insert_data($tbl, $data));
    }
}
