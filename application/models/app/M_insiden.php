<?php


defined('BASEPATH') or exit('No direct script access allowed');

class M_insiden extends CI_Model
{

    function __construct()
    {
        parent::__construct();
        $this->table = "t_insiden";
    }

    public function getDataInsiden($idinsiden = null, $filter = null)
    {
        $this->db->select('i.*, u.nama_user');
        $this->db->from($this->table . ' as i');
        $this->db->join('t_user u', 'u.id = i.id_user', 'left');
        
        if (!empty($idinsiden)) {
            $this->db->where('i.id', $idinsiden);
        }
        if (!empty($filter['filter'])) {
            $this->db->where($filter['filter']);
        }
        if (!empty($filter['order'])) {
            $this->db->order_by($filter['order']);
        } else {
            $this->db->order_by('id', 'desc');
        }
        if (!empty($filter['limit'])) {
            $this->db->limit($filter['limit'], $filter['offset']);
        }
        $this->db->order_by('id', 'desc');
        return $this->db->get()->result();
    }

    public function getDataBerkasInsiden($idinsiden = null)
    {
        $this->db->select('ib.*, i.nama_insiden, u.nama_user');
        $this->db->from('t_insiden_berkas as ib');
        $this->db->join('t_insiden i', 'i.id = ib.id_insiden', 'left');
        $this->db->join('t_user u', 'u.id = i.id_user', 'left');
        $this->db->where('ib.id_insiden', $idinsiden);
        $this->db->order_by('ib.id', 'desc');
        return $this->db->get()->result();
    }


    public function insertDataInsiden($data)
    {
        $this->db->insert($this->table, $data);
        return $this->cekPerubahan();
    }

    public function updateDataInsiden($idinsiden, $data)
    {
        $this->db->update($this->table, $data, ['id' => $idinsiden]);
        return $this->cekPerubahan();
    }

    public function deleteDataInsiden($idinsiden)
    {
        $this->db->delete($this->table, ['id' => $idinsiden]);
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

/* End of file M_insiden.php */
