<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin123 extends CI_Controller{

	function __construct(){
		parent::__construct();
		$this->load->model('m_admin');
	}

	function index(){
		$this->load->library('user_agent');
		if($this->session->has_userdata('kitab')){
			$kitab=$this->session->userdata('kitab');
			$data=[
				'versidb'	=> $this->m_main->cek_update_db($kitab),
				'kitab'		=> $kitab,
				'title'		=> $this->m_admin->kitab_get_detil($kitab, 'kitab_full')
			];
			$this->load->view('admin', $data);
		}else{
			$kitab=$this->input->post('kitab'); $pwd=$this->input->post('pwd');
			if($this->m_admin->kitab_matc_password($kitab, $pwd)){
				$this->session->set_userdata('kitab', $kitab);
				$data=[
					'versidb'	=> $this->m_main->cek_update_db($kitab),
					'kitab'		=> $kitab,
					'title'		=> $this->m_admin->kitab_get_detil($kitab, 'kitab_full')
				];
				$this->load->view('admin', $data);
			}else{
				$this->load->view('login_admin', ['arr_kitab'=>$this->m_admin->kitab_get_all()]);
			}
		}		
	}

	function logout(){
		$this->session->sess_destroy();
		redirect(base_url('admin123'));
	}
	function get_max_id($kitab){
		if(!$this->session->has_userdata('kitab')) return;
		echo $this->m_admin->get_max_id($kitab);
	}
	function add($tbl){
		if(!$this->session->has_userdata('kitab')) return;
		$nash=replace_p($this->input->post('nash'));
		$no_harokat=rem_harokat($nash);
		$no_harokat=replace_hamzah($no_harokat);
		$terjemah=replace_p($this->input->post('terjemah'));
		$data=[
			'nash'			=> $nash,
			'no_harokat'=> $no_harokat,
			'terjemah'	=> $terjemah
		];
		echo $this->m_admin->add($tbl, $data);		
	}

	function update_nash($id){
		if(!$this->session->has_userdata('kitab')) return;
		$kitab=$this->input->post('kitab');
		$nash=replace_p($this->input->post('nash'));
		//$nash=replace_petik_kurung($nash);
		$no_harokat=rem_harokat($nash);
		$no_harokat=replace_hamzah($no_harokat);
		$terjemah=replace_p2($this->input->post('terjemah'));
		$terjemah=replace_brnbsp($terjemah);
		$data = [
			'nash'=>$nash,
			'noharokat'=>$no_harokat,
			'terjemah'=>$terjemah
		];
		echo $this->m_admin->update($kitab, $data, $id);		
	}

	function update_noharokat_batch($awal=0, $akhir=0){
		if(!$this->session->has_userdata('kitab')) return;
		$kitab=$this->session->userdata('kitab');
		if($awal>0){
			$arr = $this->db->get_where($kitab, ['id>=' => $awal, 'id<=' => $akhir])->result_array();
			foreach($arr as $r){
				$no_harokat=rem_harokat($r['nash']);
				$no_harokat=replace_hamzah($no_harokat);
				echo $this->m_admin->update($kitab, ['noharokat'=>$no_harokat], $r['id']).'-';
			}
		}
	}
	function getEmptyPage($kitab){
		$sql = "select id from ".$kitab." where nash is NULL or nash = '' or terjemah is NULL or terjemah = '' limit 1";
		echo $this->db->query($sql)->row('id');
	}
	function add_page($kitab, $after_pg){
		if(!$this->session->has_userdata('kitab')) return;
		echo $this->m_admin->add_page($kitab, $after_pg);
	}

	function rem_page($kitab, $pg){
		if(!$this->session->has_userdata('kitab')) return;
		echo $this->m_admin->rem_page($kitab, $pg);
	}
	function rem_page2($kitab, $pg){
		if(!$this->session->has_userdata('kitab')) return;
		echo $this->m_admin->rem_page2($kitab, $pg);
	}

	function add_toc($kitab){
		if(!$this->session->has_userdata('kitab')) return;
		$no_harokat=rem_harokat($this->input->post('text'));
		$data=[
			'id'			=> $this->input->post('id'),
			'parent'	=> $this->input->post('parent'),
			'text'		=> $no_harokat,
			'terjemah'=> $this->input->post('terjemah')
		];
		echo $this->m_admin->add_toc($kitab, $data);
	}
	function update_toc($kitab, $id){
		if(!$this->session->has_userdata('kitab')) return;
		$no_harokat=rem_harokat($this->input->post('text'));
		$data=[
			'text'		=> $no_harokat,
			'terjemah'=> $this->input->post('terjemah')
		];
		echo $this->m_admin->update_toc($kitab, $id, $data);
	}
	function rem_toc($kitab, $id){
		if(!$this->session->has_userdata('kitab')) return;
		echo $this->m_admin->rem_toc($kitab, $id);
	}
	
	// rem all harokat from toc
	function update_all_toc($kitab){
		if(!$this->session->has_userdata('kitab')) return;
		$arr = $this->db->get($kitab.'_toc')->result_array();
		foreach($arr as $t){
			$data = ['text'=>rem_harokat($t['text'])];
			echo $this->m_admin->update_toc($kitab,$t['id'],$data).'<br/>';
		}
	}

	function update_db_server($kitab){
		if(!$this->session->has_userdata('kitab')) return;
		echo $this->m_admin->update_db_server($kitab);
	}
	function matan_biru(){
		$kitab=sesi('kitab');
		//$data = $this->m_main->get_kitab($kitab);
		$data = $this->db->not_like('nash', '%color%')->get($kitab)->result_array();
		foreach($data as $r){
			$mb=matan_biru2($r['nash']);
			$arr = ['nash'=>$mb];
			echo $r['id'].' => '. $this->m_admin->update($kitab, $arr, $r['id']).'<br>';
		}
	}
	function nasafiRemAyat($start,$end){
		$data = $this->db->select('id, nash')->from('tafsir_nasafi')->where(['id>='=>$start,'id<='=>$end])->get()->result_array();
		foreach($data as $d){
			$nash = preg_replace('/^[^>]*>\s*/', '', $d['nash']);
			$no_harokat=replace_p($nash);
			$no_harokat=rem_harokat($no_harokat);
			$no_harokat=replace_hamzah($no_harokat);
			$data = [
				'nash'=>$nash,
				'noharokat'=>$no_harokat
			];
			if($this->m_admin->update('tafsir_nasafi', $data, $d['id'])){
				echo $d['id'].' Sukses<br>';
			} else{
				echo $d['id'].' Gagal<br>';
			}
			;
		}
	}
	function gantiPetikDenganKurung(){
		if(!$this->session->has_userdata('kitab')) return;
		$data = $this->db->get(sesi('kitab'))->result_array();
		foreach($data as $d){
			$hasil = preg_replace('/"([^"]*)"/', '«$1»', $d['nash']);
			$arr = ['nash'=>$hasil];
			echo $d['id'].' => '. $this->m_admin->update(sesi('kitab'), $arr, $d['id']).'<br>';
		}
	}
	function updateDB($kitab,$curdate){
		//$curdate=date('Y-m-d');
		echo $this->db->update('terjemah_index', ['versi'=>$curdate], ['kitab'=>$kitab]);
	}
	function top_words(){
		if(!$this->session->has_userdata('kitab')) return;
		$data = $this->db->where('id>',612)->get(sesi('kitab'));
		$allText = "";

		foreach($data->result() as $row){
			$allText .= " " . $row->nash;
		}

		// Bersihkan teks: hilangkan tanda baca, ke huruf kecil
		$allText = preg_replace("/[^\p{L}\p{N}\s]/u", " ", $allText);

		// Pecah menjadi array kata
		$words = preg_split('/\s+/', trim($allText));

		// Hitung frekuensi kata
		$counts = array_count_values($words);

		// Urutkan dari terbanyak
		arsort($counts);

		// Ambil 500 kata teratas
		$top_words = array_slice($counts, 0, 500, true);

		// Tampilkan dalam tabel HTML
		echo "<table border='1' cellpadding='5' cellspacing='0'>";
		echo "<tr><th>Kata</th><th>Jumlah Muncul</th></tr>";
		foreach($top_words as $kata => $jumlah){
			echo "<tr>";
			echo "<td> " . htmlentities($kata) . " </td>";
			echo "<td align='center'>" . $jumlah . "</td>";
			echo "</tr>";
		}
		echo "</table>";
	}
    public function tambah_harakat()    {
        // Baca file mapping
        $file_path = FCPATH . 'teks_map.txt'; // Simpan file di folder root CI
        $mapping = [];

        if (file_exists($file_path)) {
            $lines = file($file_path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                if (strpos($line, '=') !== false) {
                    list($asli, $harakat) = explode('=', $line, 2);
                    $mapping[trim($asli)] = trim($harakat);
                }
            }
        } else {
            exit("File mapping tidak ditemukan!");
        }

        // Ambil semua nash
        $nash_list = $this->db->where('id>',612)->get(sesi('kitab'))->result();

        foreach ($nash_list as $row) {
            $text = $row->nash;

            // Ganti tiap kata sesuai mapping
            foreach ($mapping as $tanpa => $berharakat) {
                // Batas kata untuk huruf Arab (\b kadang tidak berfungsi sempurna di UTF-8, jadi gunakan lookbehind/lookahead)
                $pattern = '/(?<=^|[\s،؛,.؟!])' . preg_quote($tanpa, '/') . '(?=$|[\s،؛,.؟!])/u';
                $text = preg_replace($pattern, $berharakat, $text);
            }

            // Simpan hasil ke database
            $this->db->where('id', $row->id)
                     ->update(sesi('kitab'), ['nash' => $text]);
        }

        echo "Proses penambahan harakat selesai.";
    }
}
