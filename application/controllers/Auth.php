<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @property CI_Loader $load
 * @property CI_Input $input
 * @property CI_Session $session
 * @property CI_Form_validation $form_validation
 * @property CI_DB_query_builder $db
 * @property M_Auth $auths
 */

class Auth extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
		$this->load->model('M_Auth','auths');
		$this->load->model('M_Crud', 'crud');
    }

	public function index()
	{
		$this->load->view('auth/login');
	}

	public function login()
	{
		$username = $this->input->post('username');
		$password = md5($this->input->post('password'));
		$admin	  = $this->auths->admin($username,$password);
		$user	  = $this->auths->user($username,$password);
		$ahli	  = $this->auths->ahli($username,$password);
		if($admin->num_rows() > 0){
			$this->session->set_userdata('id',$admin->row()->id);
			$this->session->set_userdata('nama',$admin->row()->nama);
			$this->session->set_userdata('username',$admin->row()->username);
			$this->session->set_userdata('role','admin');
			redirect('admin');
		}elseif($user->num_rows() > 0){
			$this->session->set_userdata('id',$user->row()->id);
			$this->session->set_userdata('nama',$user->row()->nama);
			$this->session->set_userdata('username',$user->row()->username);
			$this->session->set_userdata('role','user');
			redirect('user');
		}elseif($ahli->num_rows() > 0){
			$this->session->set_userdata('id',$ahli->row()->id);
			$this->session->set_userdata('nama',$ahli->row()->nama);
			$this->session->set_userdata('username',$ahli->row()->username);
			$this->session->set_userdata('role','ahli');
			redirect('ahli');
		}else{
			$this->session->set_flashdata('pesan','Username atau password salah');
			redirect('auth');
		}
	}

	public function registrasi(){
		$this->load->view('auth/registrasi');
	}

	public function register(){
		$data = [
			'nama' => $this->input->post('nama'),
			'username' => $this->input->post('username'),
			'password' => md5($this->input->post('password'))
		];
		$this->session->set_flashdata('pesan', 'Berhasil membuat akun');
		$this->crud->insert($data, 'user');
		redirect('auth');
	}

	public function logout(){
		$this->session->sess_destroy();
		redirect('auth');
	}
}
