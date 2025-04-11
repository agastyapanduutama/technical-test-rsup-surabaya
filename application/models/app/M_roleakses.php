<?php


defined('BASEPATH') or exit('No direct script access allowed');

class M_roleakses extends CI_Model
{

    function __construct()
    {
        parent::__construct();
        $this->table = "t_role_akses";
    }

    public function getDataRoleAkses($idroleakses = null, $filter = null)
    {
        $this->db->select('ra.id, ra.id_user, ra.id_role, m.nama_user, r.nama_role');
        $this->db->from($this->table . ' ra');
        $this->db->join('t_role r', 'r.id = ra.id_role', 'left');
        $this->db->join('t_user m', 'm.id = ra.id_user', 'left');
        
        if (!empty($idroleakses)) {
            $this->db->where('ra.id', $idroleakses);
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

        $this->db->order_by('ra.id', 'desc');
        return $this->db->get()->result();
    }


    public function insertDataRoleAkses($data)
    {
        $this->db->insert($this->table, $data);
        return $this->cekPerubahan();
    }

    public function updateDataRoleAkses($idroleakses, $data)
    {
        $this->db->update($this->table, $data, ['id' => $idroleakses]);
        return $this->cekPerubahan();
    }

    public function deleteDataRoleAkses($idroleakses)
    {
        $this->db->delete($this->table, ['id' => $idroleakses]);
        return $this->cekPerubahan();
    }

    public function checkUDataByAkses($data, $iduser = null)
    {
        $this->db->from('t_role_akses');
        $this->db->where('id_role', $data['id_role']);
        $this->db->where('id_user', $data['id_user']);
        if(!empty($iduser)) {
            $this->db->where('id !=', $iduser);
        }
        return $this->db->get()->num_rows() > 0;
    }

    public function getDataRoleAksesByUser($iduser)
    {
        $this->db->from('t_role_akses');
        $this->db->where('id_user', $iduser);
        return $this->db->get()->row();
        
        
    }

    public function getDataRole($idrole)
    {
        return $this->db->get_where('t_role', ['id' => $idrole])->row();
        
    }
    
    public function getDataUser($iduser)
    {
        return $this->db->get_where('t_user', ['id' => $iduser])->row();
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

/* End of file M_roleakses.php */
