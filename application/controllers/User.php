<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('M_Auth', 'auths');
        $this->load->model('M_Crud', 'crud');
        if ($this->session->userdata('role') != 'user') {
            redirect('auth');
        }
    }

	public function index()
	{
		$data = [
            'title' => 'Dashboard',
			'subkriteria' => $this->db->query("SELECT a.*,b.id as idkriteria FROM subkriteria a, kriteria b WHERE a.id_kriteria = b.id AND b.nama_kriteria LIKE '%Jenis Kulit%'")->result(),
			'kriteria' => $this->db->query("SELECT * FROM kriteria WHERE nama_kriteria LIKE '%Jenis Kulit%'")->row(),
        ];
        $this->load->view('user/header', $data);
        $this->load->view('user/index', $data);
        $this->load->view('user/footer');
	}

	public function rekomendasi()
    {
        $id_kriteria = $this->input->post('id_kriteria');
        $nilai = $this->input->post('nilai');
        if ($id_kriteria == null) {
            redirect('user');
        }
		if($nilai == null){
			redirect('user');
		}
        $penilaian = $this->crud->get_normalisasi_data($id_kriteria, $nilai);
        $kriteria = $this->crud->get_kriteria();

        // Hitung nilai maksimum/minimum untuk setiap kriteria
        $nilai_kriteria = [];
        foreach ($kriteria as $k) {
            $nilai = array_column(
                array_filter($penilaian, function ($p) use ($k) {
                    return $p['nama_kriteria'] == $k['nama_kriteria'];
                }),
                'nilai',
            );

            $nilai_kriteria[$k['nama_kriteria']] = [
                'max' => max($nilai),
                'min' => min($nilai),
                'tren' => $k['tren'],
                'bobot' => $k['bobot'],
            ];
        }

        // Susun data penilaian dan normalisasi
        $data_normalisasi = [];
        $normalisasi_bobot = [];
        $cpi = [];
		$data_penilaian = [];

        foreach ($penilaian as $p) {
            $tren = $nilai_kriteria[$p['nama_kriteria']]['tren'];
            $min = $nilai_kriteria[$p['nama_kriteria']]['min'];
            $bobot = $nilai_kriteria[$p['nama_kriteria']]['bobot'];

			$data_penilaian[$p['nama_sunscreen']][$p['nama_kriteria']] = $p['nilai'];
            // Hitung normalisasi
            if ($tren == 'Positif') {
                $nilai_normalisasi = $p['nilai'] / $min * 100;
            } else {
                // cost
                $nilai_normalisasi = $min / $p['nilai'] * 100;
            }

            // Simpan normalisasi
            $data_normalisasi[$p['nama_sunscreen']][$p['nama_kriteria']] = round($nilai_normalisasi, 8);

            // Hitung normalisasi * bobot
            $normalisasi_bobot[$p['nama_sunscreen']][$p['nama_kriteria']] = round($nilai_normalisasi * $bobot, 8);

            // Akumulasi CPI untuk alternatif
			if (!isset($cpi[$p['nama_sunscreen']])) {
				$cpi[$p['nama_sunscreen']] = [
					'image' => $p['image'],
					'total' => 0, // Total CPI
				];
			}

            $cpi[$p['nama_sunscreen']]['total'] += $nilai_normalisasi * $bobot;
        }

        // Urutkan CPI
        uasort($cpi, function ($a, $b) {
			return $b['total'] <=> $a['total']; // Urutkan dari besar ke kecil
		});
	
        // Kirim data ke view
        $data['cpi'] = $cpi;
        $data['kriteria'] = array_column($kriteria, 'nama_kriteria');
        $data['penilaian'] = $data_penilaian;
		$data['normalisasi_bobot'] = $normalisasi_bobot;
        $data['normalisasi'] = $data_normalisasi;
		$data['nilai_kriteria'] = $nilai_kriteria;
        $data['title'] = 'Normalisasi';

        $this->load->view('user/header', $data);
        $this->load->view('user/penilaian', $data);
        $this->load->view('user/footer');
    }
}
