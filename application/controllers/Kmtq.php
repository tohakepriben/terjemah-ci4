<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kmtq extends CI_Controller {

	function index() {
		$this->load->view('lz_string');
	}

	function get_arid() {
		$data=$this->db->get('arab_indo_final')->result_array();
		$i=0;
		echo 'var dbArId = [<br>';
		foreach ($data as $r) {
			$i++;
			echo "'".str_replace("'","\'", $r['kamus'])."',";
			if ($i>500) {
				$i=0;
				echo "<br>";
			}else{
				$i++;
			}
		}
		echo ']';
	}
	function get_arar() {
		$data=$this->db->get('arab_arab_final')->result_array();
		$i=0;
		echo 'var dbArAr = [<br>';
		foreach ($data as $r) {
			$i++;
			echo "'".str_replace("'","\'", $r['kamus'])."',";
			if ($i>100) {
				$i=0;
				echo "<br>";
			}else{
				$i++;
			}
		}
		echo ']';
	}
	function get_tjalal() {
		$data=$this->db->select('id,nash')->get('tafsir_jalalain')->result_array();
		$i=0;
		echo 'var dbJalalain = [';
		foreach ($data as $r) {
			$i++;
			echo "'".str_replace("'","\'", $r['nash'])."',";
			if ($i>100) {
				$i=0;
				echo "<br>";
			}else{
				$i++;
			}
		}
		echo '];';
	}
}
