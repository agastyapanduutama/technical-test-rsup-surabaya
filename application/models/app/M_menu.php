<?php 


defined('BASEPATH') OR exit('No direct script access allowed');

class M_menu extends CI_Model {

    function __construct()
    {
        parent::__construct();
        $this->table = "t_menu";
    }

    public function getDataMenu($idmenu = null, $filter = null)
    {
        $this->db->select('id, link, nama_menu, keterangan, status');
        $this->db->from($this->table);

        if(!empty($idmenu)) {
            $this->db->where('id', $idmenu);
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
    

    public function insertDataMenu($data)
    {
        $this->db->insert($this->table, $data);
        return $this->cekPerubahan();
    }

    public function updateDataMenu($idmenu, $data)
    {
        $this->db->update($this->table, $data, ['id' => $idmenu]);
        return $this->cekPerubahan();
    }

    public function deleteDataMenu($idmenu)
    {
        $this->db->delete($this->table, ['id' => $idmenu]);
        return $this->cekPerubahan();
        
    }

    public function checkUDataByNameMenu($menuname, $idmenu = null)
    {
        $this->db->from('t_menu');
        $this->db->where('nama_menu', $menuname);
        if(!empty($idmenu)) {
            $this->db->where('id !=', $idmenu);
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

/* End of file M_menu.php */
