<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ch extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('m_ch');
	}

	function index(){
		$this->load->view('ch');
	}
	
	function insert($tbl){
		$data=[
			'id'			=> $this->input->post('id'),
			'nass'		=> $this->input->post('nass'),
			'terjemah'=> $this->input->post('terjemah')
		];
		echo $this->m_ch->insert('ch_'.$tbl, $data);
	}
}
