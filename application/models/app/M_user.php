<?php 


defined('BASEPATH') OR exit('No direct script access allowed');

class M_user extends CI_Model {

    function __construct()
    {
        parent::__construct();
        $this->table = "t_user";
    }

    public function getDataUser($iduser = null, $filter = null)
    {
        $this->db->select('id, username, nama_user, keterangan, status');
        $this->db->from($this->table);
        if(!empty($iduser)) {
            $this->db->where('id', $iduser);
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

        return $this->db->get()->result();
    }
    

    public function insertDataUser($data)
    {
        $this->db->insert($this->table, $data);
        return $this->cekPerubahan();
    }

    public function updateDataUser($iduser, $data)
    {
        $this->db->update($this->table, $data, ['id' => $iduser]);
        return $this->cekPerubahan();
    }

    public function deleteDataUser($iduser)
    {
        $this->db->delete($this->table, ['id' => $iduser]);
        return $this->cekPerubahan();
        
    }

    public function checkUDataByUsername($username, $iduser = null)
    {
        $this->db->from('t_user');
        $this->db->where('username', $username);
        if(!empty($iduser)) {
            $this->db->where('id !=', $iduser);
        }
        return $this->db->get()->num_rows();
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

/* End of file M_user.php */
