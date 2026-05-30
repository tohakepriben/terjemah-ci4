<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_ch extends CI_Model {

	function insert($tbl, $data){
		return($this->db->insert('ch_'.$tbl, $data));
	}

}
