<?php

namespace App\Controllers;

use App\Models\MUser;
use Config\Database;

class User extends BaseController
{
    private MUser $mUser;

    public function __construct()
    {
        $this->mUser = new MUser();
    }

    public function login()
    {
        $email = (string) $this->request->getPost('email');
        $password = (string) $this->request->getPost('password');

        if ($this->mUser->login($email, $password)) {
            $this->session->set(['email' => $email, 'id' => $this->get_detil($email, 'id')]);

            return $this->response->setBody('1');
        }

        return $this->response->setBody('0');
    }

    public function logout()
    {
        $this->session->destroy();

        return redirect()->to(base_url('?android=1'));
    }

    public function get_detil(string $email, string $field)
    {
        $db = Database::connect();

        return $db->table('user')->where('email', $email)->get()->getRow($field);
    }
}
