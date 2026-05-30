<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller {

	function __construct() {
		parent::__construct();
	}

	function index(){
		$this->load->library('user_agent');
		if(!$this->agent->is_mobile()){
		  if($this->input->get('force')!=1){
				$this->load->view('restricted');
			  return;
			}
		}
		$kitab=$this->input->get('kitab');
		if($kitab!=''){
			$data=[
				'versidb'	=> $this->m_main->cek_update_db($kitab),
				'kitab'		=> $kitab,
				'title'		=> $this->m_main->kitab_get_detil($kitab, 'kitab_full')
			];
			$this->load->view('main', $data);
		}
		else $this->load->view('restricted');
	}
	function get_table_size($tbl){
		echo $this->m_main->get_table_size($tbl);
	}
	function getEmptyPage($tbl){
		echo $this->m_main->getEmptyPage($tbl);
	}
	function cari($tbl){
		$re=$this->input->get('re');
		echo json_encode($this->m_main->cari($tbl, $re));
	}
	
	function cari_old($tbl){
		$key=$this->input->get('key'); 
		$key=rem_harokat($key);
		$key=replace_hamzah($key);
		$multilike=$this->input->get('multilike');
		$spasi=$this->input->get('spasi');
		$col=is_arabic($key) ? 'noharokat' : 'terjemah' ;
		echo json_encode($this->m_main->cari($tbl, $col, $key, $multilike, $spasi));
	}
	
	function baca($tbl, $id){
		echo json_encode($this->m_main->baca($tbl, $id));
	}
	function get_ids($tbl){
		echo json_encode($this->m_main->get_ids($tbl));
	}
	function get_toc($tbl){
		echo json_encode($this->m_main->get_toc($tbl.'_toc'));
	}
	function get_kitab($tbl){
		echo json_encode($this->m_main->get_kitab($tbl));
	}

	//untuk apk offline
	function cek_update_js($tbl){		
		$newVersi = $this->m_main->cek_update_db($tbl);
		echo 'if((typeof localforage !== "undefined")||(typeof dbManager.add === "undefined")){showAlert("Aplikasi kadaluarsa. Silakan update via PlayStore!")}'.
					'else{'.
						'let newVersi="'.$newVersi.'", oldVersiDb = getCurVersiDb();'.
						'if(newVersi > oldVersiDb){'.
							'if(oldVersiDb=="1990-01-01"){showAlert("Memuat data dari server");$("#div-terjemah").html("<h3>Mohon tunggu sebentar... Sedang mengambil data dari server.</h3>Jangan tutup aplikasi hingga selesai. Proses ini mungkin butuh waktu lama, tergantung koneksi jaringan internet anda.")}else{showAlert("Meng-update database...")}'.
							'let s2 = document.createElement("script");'.
							's2.src = "https://terjemah.tohakepriben.com/main/get_kitab_js/'.$tbl.'/"+oldVersiDb;'.
							'document.body.appendChild(s2);'.
							'if(typeof tryInit == "function") tryInit();'.
						'}'.
					'}';
	}

	function get_kitab_js($tbl,$oldVersi='1990-01-01'){
		$newVersi = $this->m_main->cek_update_db($tbl);
		$where = ['updated>' => $oldVersi];
		$kitab = $this->db->select('id,nash,terjemah')->from($tbl)->where($where)->get()->result_array();
		
		// KITAB
		echo 'let newKitab=[';
		foreach($kitab as $d){
			echo '{id:'.$d['id'].',nash:`'.$d['nash'].'`,terjemah:`'.$d['terjemah'].'`},';
		}
		echo '];';
		
		// TOC
		$adaPerubahanToc = $this->db->select('*')->from($tbl.'_toc')->where($where)->get()->num_rows() > 0;
		if($adaPerubahanToc){
			$toc = $this->db->select('*')->from($tbl.'_toc')->get()->result_array();
			for($i=0; $i<count($toc); $i++){
				if($toc[$i]['parent']==0) $toc[$i]['parent']='#';
			}

			echo 'let newTOC=[';
			foreach($toc as $d){
				echo '{id:'.$d['id'].',parent:"'.$d['parent'].'",text:`'.$d['text'].'`,terjemah:`'.$d['terjemah'].'`},';
			}
			echo '];';			
		}

		// EKSEKUSI UPDATE
		echo 'async function updateNewKitab(){';
		echo 'try{for(const data of newKitab){';
		echo 'await dbManager.addOrUpdate(data)';
		echo '}';
		if($adaPerubahanToc) echo 'lsSet("toc", JSON.stringify(newTOC));try{$("#toc").jstree(true).destroy();}catch(er){console.log(er)}';
		echo 'lsSet("curVersiDb","'.$newVersi.'");';
		echo 'initApp();';
		echo 'showAlert("Update data berhasil.");';
		echo 'if(typeof tryInit == "function") clearTimeout(timeOutTryInit);';
		echo '} catch(e){console.log(e)}}';
		echo 'updateNewKitab();';
		//echo ' } '.$rnd_fungsi_js;
	}
	//end apk offline

	function cek_update_db($tbl){		
		echo $this->m_main->cek_update_db($tbl);
	}
	function charcode(){
		$ch = ['ا','أ','إ','آ','ّ','َ','ً','ُ','ٌ','ِ','ٍ','ْ','ٓ','ٰ'];
		echo(json_encode($ch));
	}
	function coba(){
		$str='<code><p>mhfyu uyfjguk ilgiuy</p><p>mhfyu uyfjguk ilgiuy2222</p></code>';
		echo replace_p($str);
	}
}
