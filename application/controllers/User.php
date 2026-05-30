<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

	function __construct() {
		parent::__construct();
	}

	function login(){
		$email=$this->input->post('email');
		$password=$this->input->post('password');
		if($this->m_user->login($email,$password)){
			$this->session->set_userdata(['email'=>$email,'id'=>$this->get_detil($email,'id')]);
			echo 1;
		}else{
			echo 0;
		}
	}
	function logout(){
		$this->session->sess_destroy();
		redirect(base_url('?android=1'));
	}
	
	function get_detil($email, $field){
		$this->db->where('email', $email);
		return $this->db->get('user')->row($field);
	}
}
