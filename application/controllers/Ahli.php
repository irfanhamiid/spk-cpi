<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ahli extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('M_Auth', 'auths');
        $this->load->model('M_Crud', 'crud');
        if ($this->session->userdata('role') != 'ahli') {
            redirect('auth');
        }
    }

	public function index()
    {
        $data = [
            'title' => 'Dashboard',
			'alternatif' => $this->db->query("SELECT * FROM alternatif")->num_rows(),
			'pengguna' => $this->db->query("SELECT * FROM user")->num_rows(),
			'kriteria' => $this->db->query("SELECT * FROM kriteria")->num_rows(),
			'subkriteria' => $this->db->query("SELECT * FROM subkriteria")->num_rows(),
        ];
        $this->load->view('ahli/header', $data);
        $this->load->view('ahli/index', $data);
        $this->load->view('ahli/footer');
    }

	public function kriteria()
    {
        $data = [
            'title' => 'Kriteria',
            'kriteria' => $this->crud->get('kriteria'),
        ];
        $this->load->view('ahli/header', $data);
        $this->load->view('ahli/kriteria', $data);
        $this->load->view('ahli/footer');
    }

	public function edit_kriteria($id){
		$data = [
            'title' => 'Kriteria',
            'aksi' => 'edit',
			'kriteria' => $this->crud->get_where('kriteria',$id),
        ];
        $this->load->view('ahli/header', $data);
        $this->load->view('ahli/kriteria_form', $data);
        $this->load->view('ahli/footer');
	}

	public function update_kriteria($id){
		$data = [
			'nama_kriteria' => $this->input->post('nama_kriteria'),
			'tren'			=> $this->input->post('tren'),
			'prioritas'		=> $this->input->post('prioritas'),
		];
		$this->crud->update('kriteria',$data,$id);
		$this->session->set_flashdata('pesan','Berhasil update kriteria');
		redirect('ahli/kriteria');
	}

	public function hitung_roc()
    {
        $kriteria = $this->crud->prioritas();

        $n = count($kriteria);
        if ($n == 0) {
            $this->session->set_flashdata('pesan','Belum ada kriteria yang ditambahkan');
			redirect('ahli/kriteria');
        }

		$bobot = [];
		foreach ($kriteria as $key => $row) {
			$i = $key + 1;
			$weight = 0;
			for ($j = $i; $j <= $n; $j++) {
				$weight += 1 / $j;
			}
			$bobot[] = round($weight / $n, 2);
		}

		foreach ($kriteria as $index => $row) {
			$this->crud->update_bobot($row['id'], $bobot[$index]);
		}

		$this->session->set_flashdata('pesan','Berhasil update bobot kriteria');
		redirect('ahli/kriteria');
    }

	public function subkriteria($id){
		$data = [
            'title' => 'Subkriteria',
            'kriteria' => $this->crud->get_where('kriteria',$id),
			'subkriteria' => $this->crud->subkriteria_where($id),
        ];
        $this->load->view('ahli/header', $data);
        $this->load->view('ahli/subkriteria', $data);
        $this->load->view('ahli/footer');
	}

	public function edit_subkriteria($id){
		$data = [
            'title' => 'Subkriteria',
            'aksi' => 'edit',
			'subkriteria' => $this->crud->get_where('subkriteria',$id),
        ];
        $this->load->view('ahli/header', $data);
        $this->load->view('ahli/subkriteria_form', $data);
        $this->load->view('ahli/footer');
	}

	public function update_subkriteria($id){
		$data = [
			'nama_sub' => $this->input->post('nama_sub'),
			'prioritas'		=> $this->input->post('prioritas'),
		];
		$this->crud->update('subkriteria',$data,$id);
		$this->session->set_flashdata('pesan','Berhasil update subkriteria');
		redirect('ahli/subkriteria/'.$this->input->post('id_kriteria'));
	}

	public function hitung_nilai($id)
    {
        $kriteria = $this->crud->sprioritas($id);

        $n = count($kriteria);
        if ($n == 0) {
            $this->session->set_flashdata('pesan','Belum ada subkriteria yang ditambahkan');
			redirect('ahli/subkriteria/'.$id);
        }

		$bobot = [];
		foreach ($kriteria as $key => $row) {
			$i = $key + 1;
			$weight = 0;
			for ($j = $i; $j <= $n; $j++) {
				$weight += 1 / $j;
			}
			$bobot[] = round($weight / $n, 2);
		}

		foreach ($kriteria as $index => $row) {
			$this->crud->update_nilai($row['id'], $bobot[$index]);
		}

		$this->session->set_flashdata('pesan','Berhasil update nilai subkriteria');
		redirect('ahli/subkriteria/'.$id);
    }

	public function penilaian()
    {
        $data = [
            'title' => 'Penilaian',
            'alternatif' => $this->crud->get('alternatif'),
            'kriteria' => $this->crud->get('kriteria'),
            'penilaian' => $this->crud->get_penilaian(),
        ];
        $this->load->view('ahli/header', $data);
        $this->load->view('ahli/penilaian', $data);
        $this->load->view('ahli/footer');
    }
}
