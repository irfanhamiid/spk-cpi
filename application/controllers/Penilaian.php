<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Penilaian extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('M_Auth', 'auths');
        $this->load->model('M_Crud', 'crud');
        if ($this->session->userdata('role') != 'admin') {
            redirect('auth');
        }
    }

    public function index()
    {
        $data = [
            'title' => 'Penilaian',
            'alternatif' => $this->crud->get('alternatif'),
            'kriteria' => $this->crud->get('kriteria'),
            'penilaian' => $this->crud->get_penilaian(),
        ];
        $this->load->view('admin/header', $data);
        $this->load->view('admin/penilaian', $data);
        $this->load->view('admin/footer');
    }

    public function tambah()
    {
        $data = [
            'title' => 'Penilaian',
            'aksi' => 'add',
            'alternatif' => $this->crud->alternatif(),
            'kriteria' => $this->crud->get('kriteria'),
        ];
        $this->load->view('admin/header', $data);
        $this->load->view('admin/penilaian_form', $data);
        $this->load->view('admin/footer');
    }

    public function simpan()
    {
        $id_alternatif = $this->input->post('id_alternatif');
        $id_kriteria = $this->input->post('id_kriteria');
        $nilai = $this->input->post('nilai');

        if (!empty($id_alternatif) && !empty($id_kriteria) && !empty($nilai)) {
            foreach ($id_kriteria as $key => $id_krit) {
                $data = [
                    'id_alternatif' => $id_alternatif,
                    'id_kriteria' => $id_krit,
                    'nilai' => $nilai[$key],
                ];
                $this->crud->insert_penilaian($data);
            }
            $this->session->set_flashdata('pesan', 'Data berhasil disimpan!');
        } else {
            $this->session->set_flashdata('pesan', 'Data tidak lengkap. Mohon isi semua input!');
        }

        redirect('penilaian');
    }

	public function update()
	{
		$id_alternatif = $this->input->post('id_alternatif');
		$this->crud->deletepenilaian($id_alternatif);
        $id_kriteria = $this->input->post('id_kriteria');
        $nilai = $this->input->post('nilai');
		if (!empty($id_alternatif) && !empty($id_kriteria) && !empty($nilai)) {
            foreach ($id_kriteria as $key => $id_krit) {
                $data = [
                    'id_alternatif' => $id_alternatif,
                    'id_kriteria' => $id_krit,
                    'nilai' => $nilai[$key],
                ];
				
                $this->crud->insert_penilaian($data);
            }
            $this->session->set_flashdata('pesan', 'Data berhasil disimpan!');
        } else {
            $this->session->set_flashdata('pesan', 'Data tidak lengkap. Mohon isi semua input!');
        }

        redirect('penilaian');
	}

	public function edit($id)
	{
		$data = [
		'title' => 'Penilaian',
		'data' => $this->db->query("SELECT * FROM penilaian WHERE id_alternatif='$id'")->row(),
		'kriteria' => $this->crud->get('kriteria'),
		'alternatif' => $this->crud->get_where('alternatif',$id),
		];
		$this->load->view('admin/header', $data);
        $this->load->view('admin/penilaian_formedit', $data);
        $this->load->view('admin/footer');
	}

    public function hapus($id)
    {
        $this->crud->deletepenilaian($id);
        redirect('penilaian');
    }

    public function pilihkriteria()
    {
        $data = [
            'title' => 'Dashboard',
            'subkriteria' => $this->db->query("SELECT a.*,b.id as idkriteria FROM subkriteria a, kriteria b WHERE a.id_kriteria = b.id AND b.nama_kriteria LIKE '%Jenis Kulit%'")->result(),
            'kriteria' => $this->db->query("SELECT * FROM kriteria WHERE nama_kriteria LIKE '%Jenis Kulit%'")->row(),
        ];
        $this->load->view('admin/header', $data);
        $this->load->view('admin/normalisasi_form', $data);
        $this->load->view('admin/footer');
    }

    public function normalisasi()
    {
        $id_kriteria = $this->input->post('id_kriteria');
        $nilai = $this->input->post('nilai');
        if ($id_kriteria == null) {
            redirect('penilaian/pilihkriteria');
        }
		if($nilai == null){
			redirect('penilaian/pilihkriteria');
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
            $id_alternatif = $p['id_alternatif'];
            $tren = $nilai_kriteria[$p['nama_kriteria']]['tren'];
            $min = $nilai_kriteria[$p['nama_kriteria']]['min'];
            $bobot = $nilai_kriteria[$p['nama_kriteria']]['bobot'];
			$nama_sunscreen = $p['nama_sunscreen'];

            $data_penilaian[$p['nama_sunscreen']][$p['nama_kriteria']] = $p['nilai'];
            // Hitung normalisasi
            if ($tren == 'Positif') {
                $nilai_normalisasi = ($p['nilai'] / $min) * 100;
            } else {
                // cost
                $nilai_normalisasi = ($min / $p['nilai']) * 100;
            }

            // Simpan normalisasi dengan nama_sunscreen
            if (!isset($data_normalisasi[$id_alternatif])) {
                $data_normalisasi[$id_alternatif] = [
                    'nama_sunscreen' => $nama_sunscreen,
                    'normalisasi' => [],
                ];
            }
            $data_normalisasi[$id_alternatif]['normalisasi'][$p['nama_kriteria']] = round($nilai_normalisasi, 8);

            // Simpan normalisasi * bobot
            if (!isset($normalisasi_bobot[$id_alternatif])) {
                $normalisasi_bobot[$id_alternatif] = [
                    'nama_sunscreen' => $nama_sunscreen,
                    'normalisasi_bobot' => [],
                ];
            }
            $normalisasi_bobot[$id_alternatif]['normalisasi_bobot'][$p['nama_kriteria']] = round($nilai_normalisasi * $bobot, 8);

            // Akumulasi CPI untuk alternatif
            if (!isset($cpi[$id_alternatif])) {
                $cpi[$id_alternatif] = [
                    'nama_sunscreen' => $p['nama_sunscreen'],
                    'image' => $p['image'],
                    'total' => 0,
                ];
            }

            $cpi[$id_alternatif]['total'] += $nilai_normalisasi * $bobot;
        }

        foreach ($cpi as $id_alternatif => $data) {
            $existing_result = $this->crud->get_hasil_by_alternatif($id_alternatif);

            if ($existing_result) {
                // Update jika data sudah ada
                $this->crud->update_hasil($id_alternatif, round($data['total'],2));
            } else {
                // Insert jika data belum ada
                $this->crud->insert_hasil($id_alternatif, round($data['total'],2));
            }
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

        $this->load->view('admin/header', $data);
        $this->load->view('admin/normalisasi', $data);
        $this->load->view('admin/footer');
    }
}
