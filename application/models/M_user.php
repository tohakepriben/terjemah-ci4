<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_user extends CI_Model {

	function login($email,$password){
		$this->db->where(['email'=>$email, 'password'=>$password]);
		return $this->db->get('user')->num_rows()>0;
	}

}
