<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Hadits extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('m_hadits');
	}

	function index(){
		$tables = 'hadits_bukhori,hadits_muslim,hadits_nasai,hadits_abu_dawud,hadits_tirmidzi,hadits_ibnu_majah,hadits_muwatho,hadits_syafii,hadits_ahmad,hadits_darimi,hadits_riyadlus_sholihin';
		$data = ['tables'=>$tables];
		$this->load->view('hadits', $data);
	}
	function get($nd){
		echo(json_encode($this->m_hadits->get($nd)));
	}
	function get_toc($nd){
		echo(json_encode($this->m_hadits->get_toc($nd)));
	}

}
