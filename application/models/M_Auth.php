<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_Auth extends CI_Model
{

	public function admin($username,$password)
	{
		$this->db->select('*');
        $this->db->from('admin');
        $this->db->where('username', $username);
        $this->db->where('password', $password);
        $query = $this->db->get();
        return $query;
	}

	public function user($username,$password)
	{
		$this->db->select('*');
        $this->db->from('user');
        $this->db->where('username', $username);
        $this->db->where('password', $password);
        $query = $this->db->get();
        return $query;
	}

	public function ahli($username,$password)
	{
		$this->db->select('*');
        $this->db->from('ahli_pakar');
        $this->db->where('username', $username);
        $this->db->where('password', $password);
        $query = $this->db->get();
        return $query;
	}
	
}
