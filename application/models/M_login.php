<?php 


defined('BASEPATH') OR exit('No direct script access allowed');

class M_login extends CI_Model {


    public function getUserByUsername($username)
    {
        $this->db->from('t_user');
        $this->db->where('username', $username);
        return $this->db->get()->row();
    }

    public function getUserRole($iduser)
    {
        $this->db->from('t_role_akses ra');
        $this->db->join('t_role r', 'ra.id_role = r.id', 'left');
        $this->db->where('ra.id_user', $iduser);
        return $this->db->get()->row();
    }

    public function getUserByCookie($iduser)
    {
        $user = $this->db->get_where('t_user',[$this->req->encAcak('id') => $iduser])->row();
        return $user;
    }

}

/* End of file M_login.php */
