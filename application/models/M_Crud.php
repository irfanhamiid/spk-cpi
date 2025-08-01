<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_Crud extends CI_Model
{
    public function get($table)
    {
        return $this->db->query("SELECT * FROM $table")->result();
    }

    public function get_where($table, $id)
    {
        return $this->db->query("SELECT * FROM $table WHERE id=$id")->row();
    }

    public function insert($data, $table)
    {
        $this->db->insert($table, $data);
    }

    public function prioritas()
    {
        $this->db->order_by('prioritas', 'ASC');
        return $this->db->get('kriteria')->result_array();
    }

    public function sprioritas($id)
    {
        return $this->db->query("SELECT * FROM subkriteria WHERE id_kriteria='$id' ORDER BY prioritas ASC")->result_array();
    }

    public function update_bobot($id, $bobot)
    {
        $this->db->where('id', $id);
        $this->db->update('kriteria', ['bobot' => $bobot]);
    }

    public function update_nilai($id, $bobot)
    {
        $this->db->where('id', $id);
        $this->db->update('subkriteria', ['nilai' => $bobot]);
    }

    public function update($table, $data, $id)
    {
        $this->db->where('id', $id);
        $this->db->update($table, $data);
    }

    public function delete($table, $id)
    {
        $this->db->where('id', $id);
        $this->db->delete($table);
    }

    public function subkriteria()
    {
        return $this->db->query('SELECT a.*,b.nama_kriteria FROM subkriteria a,kriteria b WHERE a.id_kriteria = b.id')->result();
    }

    public function subkriteria_where($id)
    {
        return $this->db->query("SELECT a.*,b.nama_kriteria,b.id as id_kriteria FROM subkriteria a,kriteria b WHERE a.id_kriteria = b.id AND b.id=$id")->result();
    }

    public function insert_penilaian($data)
    {
        $this->db->insert('penilaian', $data);
    }

	public function update_penilaian($data, $id_alternatif, $id_kriteria)
    {
        $this->db->where('id_alternatif', $id_alternatif);
		$this->db->where('id_kriteria',$id_kriteria);
        $this->db->update('penilaian', $data);
    }

    public function get_penilaian()
    {
        $this->db->select('a.nama_sunscreen,a.id as id_alternatif,GROUP_CONCAT(p.id_kriteria ORDER BY p.id_kriteria) AS id_kriteria, GROUP_CONCAT(p.nilai ORDER BY p.id_kriteria) AS nilai');
        $this->db->from('penilaian p');
        $this->db->join('alternatif a', 'a.id = p.id_alternatif', 'left');
        $this->db->group_by('a.id');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function deletepenilaian($id)
    {
        $this->db->where('id_alternatif', $id);
        $this->db->delete('penilaian');
    }

    public function alternatif()
    {
        $this->db->select('*');
        $this->db->from('alternatif');
        $this->db->where('id NOT IN (SELECT id_alternatif FROM penilaian)', null, false);
        $query = $this->db->get();
        return $query->result();
    }

    public function get_penilaians()
    {
        return $this->db->get('penilaian')->result_array();
    }

    public function get_kriteria()
    {
        return $this->db->get('kriteria')->result_array();
    }

    public function get_normalisasi_data($id_kriteria, $nilai)
    {
        // Cari id_alternatif yang memiliki id_kriteria dan nilai yang sesuai
        $this->db->select('id_alternatif');
        $this->db->from('penilaian');
        $this->db->where('id_kriteria', $id_kriteria);
        $this->db->where('nilai', $nilai);
        $subquery = $this->db->get_compiled_select();

        // Query utama untuk mengambil semua data berdasarkan id_alternatif dari subquery
        $this->db->select('a.nama_sunscreen, k.nama_kriteria, p.nilai,a.image,p.id_alternatif');
        $this->db->from('penilaian p');
        $this->db->join('alternatif a', 'p.id_alternatif = a.id', 'inner');
        $this->db->join('kriteria k', 'p.id_kriteria = k.id', 'inner');
        $this->db->where("p.id_alternatif IN ($subquery)", null, false);

        return $this->db->get()->result_array();
    }

    public function get_id_alternatif_by_name($nama_sunscreen)
    {
        $this->db->select('id');
        $this->db->from('alternatif');
        $this->db->where('LOWER(TRIM(nama_sunscreen))', strtolower(trim($nama_sunscreen))); // Normalisasi nama
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->row()->id;
        }

        return null;
    }

    public function get_hasil_by_alternatif($id_alternatif)
    {
        $this->db->where('id_alternatif', $id_alternatif);
        $query = $this->db->get('hasil');
        return $query->row_array();
    }

    public function update_hasil($id_alternatif, $nilai)
    {
        $this->db->where('id_alternatif', $id_alternatif);
        $this->db->update('hasil', ['nilai' => $nilai]);
    }

    public function insert_hasil($id_alternatif, $nilai)
    {
        $this->db->insert('hasil', [
            'id_alternatif' => $id_alternatif,
            'nilai' => $nilai,
        ]);
    }
}
