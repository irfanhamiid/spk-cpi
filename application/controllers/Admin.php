<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin extends CI_Controller
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
            'title' => 'Dashboard',
			'alternatif' => $this->db->query("SELECT * FROM alternatif")->num_rows(),
			'pengguna' => $this->db->query("SELECT * FROM user")->num_rows(),
			'kriteria' => $this->db->query("SELECT * FROM kriteria")->num_rows(),
			'subkriteria' => $this->db->query("SELECT * FROM subkriteria")->num_rows(),
        ];
        $this->load->view('admin/header', $data);
        $this->load->view('admin/index', $data);
        $this->load->view('admin/footer');
    }

    // Kriteria

    public function kriteria()
    {
        $data = [
            'title' => 'Kriteria',
            'kriteria' => $this->crud->get('kriteria'),
        ];
        $this->load->view('admin/header', $data);
        $this->load->view('admin/kriteria', $data);
        $this->load->view('admin/footer');
    }

    public function tambah_kriteria()
    {
        $data = [
            'title' => 'Kriteria',
            'aksi' => 'add',
        ];
        $this->load->view('admin/header', $data);
        $this->load->view('admin/kriteria_form', $data);
        $this->load->view('admin/footer');
    }

    public function simpan_kriteria()
    {
        $data = [
            'nama_kriteria' => $this->input->post('nama_kriteria'),
            'prioritas' => $this->input->post('prioritas'),
            'tren' => $this->input->post('tren'),
        ];
        $this->crud->insert($data, 'kriteria');
        $this->session->set_flashdata('pesan', 'Berhasil tambah kriteria');
        redirect('admin/kriteria');
    }

    public function hitung_roc()
    {
        $kriteria = $this->crud->prioritas();

        $n = count($kriteria);
        if ($n == 0) {
            $this->session->set_flashdata('pesan','Belum ada kriteria yang ditambahkan');
			redirect('admin/kriteria');
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
		redirect('admin/kriteria');
    }

	public function edit_kriteria($id){
		$data = [
            'title' => 'Kriteria',
            'aksi' => 'edit',
			'kriteria' => $this->crud->get_where('kriteria',$id),
        ];
        $this->load->view('admin/header', $data);
        $this->load->view('admin/kriteria_form', $data);
        $this->load->view('admin/footer');
	}

	public function update_kriteria($id){
		$data = [
			'nama_kriteria' => $this->input->post('nama_kriteria'),
			'tren'			=> $this->input->post('tren'),
			'prioritas'		=> $this->input->post('prioritas'),
		];
		$this->crud->update('kriteria',$data,$id);
		$this->session->set_flashdata('pesan','Berhasil update kriteria');
		redirect('admin/kriteria');
	}

	public function hapus_kriteria($id){
		$this->crud->delete('kriteria',$id);
		$this->session->set_flashdata('pesan','Berhasil hapus kriteria');
		redirect('admin/kriteria');
	}

	// Subkriteria

	public function subkriteria($id){
		$data = [
            'title' => 'Subkriteria',
            'kriteria' => $this->crud->get_where('kriteria',$id),
			'subkriteria' => $this->crud->subkriteria_where($id),
        ];
        $this->load->view('admin/header', $data);
        $this->load->view('admin/subkriteria', $data);
        $this->load->view('admin/footer');
	}

	public function tambah_subkriteria($id){
		$data = [
            'title' => 'Subkriteria',
            'kriteria' => $this->crud->get_where('kriteria',$id),
			'subkriteria' => $this->crud->subkriteria_where($id),
			'aksi' => 'add'
        ];
        $this->load->view('admin/header', $data);
        $this->load->view('admin/subkriteria_form', $data);
        $this->load->view('admin/footer');
	}

	public function simpan_subkriteria(){
		$data = [
			'nama_sub' => $this->input->post('nama_sub'),
			'prioritas' => $this->input->post('prioritas'),
			'id_kriteria' => $this->input->post('id_kriteria'),
		];
		$this->crud->insert($data, 'subkriteria');
        $this->session->set_flashdata('pesan', 'Berhasil tambah subkriteria');
        redirect('admin/subkriteria/'.$this->input->post('id_kriteria'));
	}

	public function edit_subkriteria($id){
		$data = [
            'title' => 'Subkriteria',
            'aksi' => 'edit',
			'subkriteria' => $this->crud->get_where('subkriteria',$id),
        ];
        $this->load->view('admin/header', $data);
        $this->load->view('admin/subkriteria_form', $data);
        $this->load->view('admin/footer');
	}

	public function update_subkriteria($id){
		$data = [
			'nama_sub' => $this->input->post('nama_sub'),
			'prioritas'		=> $this->input->post('prioritas'),
		];
		$this->crud->update('subkriteria',$data,$id);
		$this->session->set_flashdata('pesan','Berhasil update subkriteria');
		redirect('admin/subkriteria/'.$this->input->post('id_kriteria'));
	}

	public function hapus_subkriteria($id,$id_kriteria){
		$this->crud->delete('subkriteria',$id);
		$this->session->set_flashdata('pesan','Berhasil hapus subkriteria');
		redirect('admin/subkriteria/'.$id_kriteria);
	}

	public function hitung_nilai($id)
    {
        $kriteria = $this->crud->sprioritas($id);

        $n = count($kriteria);
        if ($n == 0) {
            $this->session->set_flashdata('pesan','Belum ada subkriteria yang ditambahkan');
			redirect('admin/subkriteria/'.$id);
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
		redirect('admin/subkriteria/'.$id);
    }

	public function alternatif(){
		$data = [
            'title' => 'Alternatif',
            'alternatif' => $this->crud->get('alternatif'),
        ];
        $this->load->view('admin/header', $data);
        $this->load->view('admin/alternatif', $data);
        $this->load->view('admin/footer');
	}
	
	public function simpan_alternatif(){

		$config['upload_path'] = './assets/img';
        $config['allowed_types'] = 'jpg|jpeg|webp|png';

        $rand = mt_rand(10000, 99999);

        $this->load->library('upload');
        $this->upload->initialize($config);

        if (!$this->upload->do_upload('image')) {
            $error = ['error' => $this->upload->display_errors()];
            print_r($error);
            exit();
        }

        $upload_data = $this->upload->data();
        $file_extension = $upload_data['file_ext'];
        $config['file_name'] = 'foto_' . $rand . $file_extension;
        $data = [
            'nama_sunscreen' => $this->input->post('nama_sunscreen'),
            'image' => $upload_data['file_name'],
        ];
		$this->crud->insert($data, 'alternatif');
        $this->session->set_flashdata('pesan', 'Berhasil tambah alternatif');
        redirect('admin/alternatif');
	}

	public function update_alternatif($id){

		if ($_FILES['image']['name'] != '') {
            $config['upload_path'] = './assets/img';
            $config['allowed_types'] = 'jpg|jpeg|webp|png';

            $rand = mt_rand(10000, 99999);

            $this->load->library('upload');
            $this->upload->initialize($config);

            if (!$this->upload->do_upload('image')) {
                $error = ['error' => $this->upload->display_errors()];
                print_r($error);
                exit();
            }

            $upload_data = $this->upload->data();
            $file_extension = $upload_data['file_ext'];
            $config['file_name'] = 'foto_' . $rand . $file_extension;
            $data = [
                'nama_sunscreen' => $this->input->post('nama_sunscreen'),
                'image' => $upload_data['file_name'],
            ];
        } else {
            $data = [
                'nama_sunscreen' => $this->input->post('nama_sunscreen'),
            ];
        }
		$this->crud->update('alternatif',$data,$id);
		$this->session->set_flashdata('pesan','Berhasil update alternatif');
		redirect('admin/alternatif');
	}

	public function hapus_alternatif($id){
		$this->crud->delete('alternatif',$id);
		$this->session->set_flashdata('pesan','Berhasil hapus alternatif');
		redirect('admin/alternatif');
	}
}
