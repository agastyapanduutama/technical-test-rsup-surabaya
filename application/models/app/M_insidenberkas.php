<?php


defined('BASEPATH') or exit('No direct script access allowed');

class M_insidenberkas extends CI_Model
{

    function __construct()
    {
        parent::__construct();
        $this->table = "t_insiden_berkas";
    }

    public function getDataInsiden($idinsiden = null)
    {
        $this->db->select('i.id');
        $this->db->from('t_insiden ' . ' as i');
        $this->db->where('i.id', $idinsiden);
        return $this->db->get()->num_rows();
    }

    public function getDataInsidenBerkas($idinsidenberkas = null, $idinsiden = null)
    {
        $this->db->select('ib.*, i.nama_insiden, u.nama_user');
        $this->db->from('t_insiden_berkas as ib');
        $this->db->join('t_insiden i', 'i.id = ib.id_insiden', 'left');
        $this->db->join('t_user u', 'u.id = i.id_user', 'left');
        
        if(!empty($idinsidenberkas)) {
            $this->db->where('ib.id', $idinsidenberkas);
        }
        if(!empty($idinsiden)) {
            $this->db->where('ib.id_insiden', $idinsiden);
        }



        $this->db->order_by('ib.id', 'desc');
        return $this->db->get()->result();
    }

    public function insertDataInsidenBerkas($data)
    {
        $this->db->insert($this->table, $data);
        return $this->cekPerubahan();
    }

    public function updateDataInsidenBerkas($idinsidenberkas, $data)
    {
        $this->db->update($this->table, $data, ['id' => $idinsidenberkas]);
        return $this->cekPerubahan();
    }

    public function deleteDataInsidenBerkas($idinsidenberkas)
    {
        $this->db->delete($this->table, ['id' => $idinsidenberkas]);
        return $this->cekPerubahan();
    }

    function cekPerubahan()
    {
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }
}

/* End of file M_insidenberkas.php */
