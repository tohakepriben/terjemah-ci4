<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_main extends CI_Model {
	function get_table_size($tbl){
		$this->db->select('(data_length + index_length) AS size');
		$this->db->from('information_schema.TABLES');
		$this->db->where('table_name', $tbl);
		return $this->db->get()->row('size');
	}
		
	function cek_update_db($tbl){
		$this->db->where('kitab', $tbl);
		return $this->db->get('terjemah_index')->row('versi');
	}
	function getEmptyPage($tbl){
		$sql = "select id from ".$tbl." where nash is NULL or nash = '' or terjemah is NULL or terjemah = '' limit 1";
		return $this->db->query($sql)->row('id');
	}
	function cari($tbl, $re){
		$ret = [];
		$data = $this->db->get($tbl)->result_array();
		foreach($data as $r){
			$nash=replace_p($r['nash']);
			$nash=rem_harokat($nash);
			$nash=replace_hamzah($nash);
			$terjemah=replace_p($r['terjemah']);
			if(preg_match($re, $nash.' '.$terjemah)) array_push($ret, $r['id']);
		}
		return $ret;
	}

	function prev($tbl, $id){
		$this->db->where('id<', $id);
		$this->db->order_by('id', 'desc');
		$this->db->limit(1);
		return $this->db->get($tbl)->result_array();		
	}

	function next($tbl, $id){
		$this->db->where('id>', $id);
		$this->db->limit(1);
		return $this->db->get($tbl)->result_array();		
	}

	function last_id($tbl){
		$this->db->order_by('id', 'desc');
		$this->db->limit(1);
		return $this->db->get($tbl)->field('id');		
	}
 
	function get_ids($tbl){
		$this->db->select('id');
		return $this->db->get($tbl)->result_array();
	}

	function baca($tbl, $id){
		return $this->db->get_where($tbl, ['id'=>$id])->result_array();		
	}
	
	function get_toc($tbl){
//		$sql = 'select id, IF(parent=0, "#", parent), CONCAT(text,"<label>",id,"</label>","<p>",terjemah,"</p>") as text from '.$tbl.' order by id asc';
//		return $this->db->query($sql)->result_array();
		$this->db->select('id, if(parent=0, "#", parent) as parent, CONCAT(text,"<label>",id,"</label>","<p>",terjemah,"</p>") as text');
		$this->db->order_by('id', 'ASC');
		return $this->db->get($tbl)->result_array();
	}
	function get_kitab($tbl){
		$this->db->order_by('id');
		return $this->db->get($tbl)->result_array();		
	}
	function kitab_get_detil($kitab, $row){
		return $this->db->get_where('terjemah_index', ['kitab'=>$kitab])->row($row);
	}
	
}
