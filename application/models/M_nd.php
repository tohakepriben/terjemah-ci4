<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_nd extends CI_Model {

	function get($nd){
		return($this->db->get('nd_'.$nd)->result_array());
	}

	function get_toc($nd){
		$this->db->select('id, cek_root(parent) as parent, CONCAT(text,"<label>",id,"</label>") as text');
		$this->db->order_by('id', 'ASC');
		return $this->db->get('nd_'.$nd.'_toc')->result_array();
	}

}
