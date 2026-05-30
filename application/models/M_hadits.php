<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_hadits extends CI_Model {


	function get($nd){
		return $this->db
						->select('id, CONCAT("<div>",nash,"</div><div>",terjemah,"</div>") as nd')
						->get($nd)->result_array();
	}

	function get_toc($nd){
		return $this->db
						->select('id, cek_root(parent) as parent, CONCAT(text,"<label>",id,"</label>") as text')
						->order_by('id', 'ASC')
						->get($nd.'_toc')->result_array();
	}
}
