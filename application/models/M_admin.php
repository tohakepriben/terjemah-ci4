<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_admin extends CI_Model {

	function get_max_id($kitab){
		$this->db->select('max(id) as max');
		return $this->db->get($kitab)->row('max');
	}

	function add($kitab, $data){
		return $this->db->insert($kitab, $data);
	}

	function update($kitab, $data, $id, $col='id'){
		return $this->db->update($kitab, $data, [$col=>$id]);		
	}
	function add_page($kitab, $after_pg){
		$sql1 = 'update '.$kitab.' set id = id + 1 where id > '.$after_pg.' order by id desc';
		$sql2 = 'update '.$kitab.'_toc set id = id + 1 where id > '.$after_pg.' order by id desc';
		$sql3 = 'update '.$kitab.'_toc set parent = parent + 1 where parent > '.$after_pg.' order by parent desc';
		$ret = $this->db->query($sql1) && $this->db->query($sql2) && $this->db->query($sql3);
		return $ret && $this->add($kitab, ['id'=>$after_pg+1, 'nash'=>'']);
	}
	function rem_page($kitab, $pg){
		if($this->db->delete($kitab, ['id'=>$pg])){
			$sql1 = 'update '.$kitab.' set id = id - 1 where id > '.$pg.' order by id asc';
			$sql2 = 'update '.$kitab.'_toc set id = id - 1 where id > '.$pg.' order by id asc';
			$sql3 = 'update '.$kitab.'_toc set parent = parent - 1 where parent > '.$pg.' order by parent asc';
			$ret = $this->db->query($sql1) && $this->db->query($sql2) && $this->db->query($sql3);
			return $ret;
		}
	}
	function rem_page2($kitab, $pg){
			$sql2 = 'update '.$kitab.'_toc set id = id - 1 where id > '.$pg.' order by id asc';
			$sql3 = 'update '.$kitab.'_toc set parent = parent - 1 where parent > '.$pg.' order by parent asc';
			$ret = $this->db->query($sql2) && $this->db->query($sql3);
			return $ret;
	}
	function add_toc($kitab, $data){
		return $this->db->insert($kitab.'_toc', $data);
	}
	function update_toc($kitab, $id, $data){
		return $this->db->update($kitab.'_toc', $data, ['id' => $id]);
	}
	function rem_toc_child($kitab){
		$sql_cnt='select count(id) as cnt from '.$kitab.'_toc where parent>0 and parent not in(select id from '.$kitab.'_toc)';
		if($this->db->query($sql_cnt)->row('cnt')>0){
			$sql_dlt='delete from '.$kitab.'_toc where parent>0 and parent not in(select id from '.$kitab.'_toc)';
			$this->db->query($sql_dlt);
			$this->rem_toc_child($kitab);
		}
	}
	function rem_toc($kitab, $id){
		$this->db->delete($kitab.'_toc', ['id'=>$id]);
		$this->rem_toc_child($kitab);
		return 1;
	}
	function update_db_server($kitab){
		$curdate=date('Y-m-d');
		$this->db->update('terjemah_index', ['versi'=>$curdate], ['kitab'=>$kitab]);
		return $curdate;
	}
	function kitab_matc_password($kitab, $password){
		$this->db->where(['kitab'=>$kitab, 'password'=>$password]);
		return $this->db->get('terjemah_index')->num_rows() > 0;
	}
	function kitab_get_all(){
		$this->db->order_by('kitab');
		return $this->db->get('terjemah_index')->result_array();
	}
	function kitab_get_detil($kitab, $row){
		return $this->db->get_where('terjemah_index', ['kitab'=>$kitab])->row($row);
	}
}
