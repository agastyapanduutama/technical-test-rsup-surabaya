<?php 


defined('BASEPATH') OR exit('No direct script access allowed');

class M_role extends CI_Model {

    function __construct()
    {
        parent::__construct();
        $this->table = "t_role";
    }

    public function getDataRole($idrole = null, $filter = null)
    {
        $this->db->select('id, nama_role, keterangan, hak_akses');
        $this->db->from($this->table);
        if(!empty($idrole)) {
            $this->db->where('id', $idrole);
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
    

    public function insertDataRole($data)
    {
        $this->db->insert($this->table, $data);
        return $this->cekPerubahan();
    }

    public function updateDataRole($idrole, $data)
    {
        $this->db->update($this->table, $data, ['id' => $idrole]);
        return $this->cekPerubahan();
    }

    public function deleteDataRole($idrole)
    {
        $this->db->delete($this->table, ['id' => $idrole]);
        return $this->cekPerubahan();
        
    }

    public function checkUDataByNameRole($rolename, $idrole = null)
    {
        $this->db->from('t_role');
        $this->db->where('nama_role', $rolename);
        if(!empty($idrole)) {
            $this->db->where('id !=', $idrole);
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

/* End of file M_role.php */
