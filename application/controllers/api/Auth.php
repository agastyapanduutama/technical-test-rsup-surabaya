<?php

defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

use Restserver\Libraries\REST_Controller;
class Auth extends REST_Controller
{

    function __construct($config = 'rest')
    {
        parent::__construct($config);
        $this->load->model('M_login', 'login');
    }


    public function index_post()
    {
        // cek validasi form login
        $this->load->library('form_validation');
        $this->form_validation->set_rules('username', 'Username', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->response(array('status' => 'fail', 'message' => validation_errors()), 400);
        }

        // cek apakah user tersebut ada atau tidak
        $username = $this->input->post('username', TRUE);
        $user = $this->login->getUserByUsername($username);

        // verify password
        if ($user) {
            if (password_verify($this->input->post('password', TRUE), $user->password)) {
                // apakah user aktif atau tidak
                if ($user->status == 0) {
                    $this->response(array('status' => 'fail', 'message' => 'Akun anda tidak aktif!'), 400);
                } else {

                    // ambil rolenya apa
                    $akses = $this->login->getUserRole($user->id);
                    
                    // $this->req->print($akses);

                    // set token autentikasi
                    $token = bin2hex(random_bytes(32));
                    $data = array(
                        'token' => $token . '-' .base64_encode($akses->hak_akses),
                        'id_user' => $user->id,
                        'expired_at' => date('Y-m-d H:i:s', strtotime('+1 hour'))
                    );
                    $this->db->insert('token', $data);
                    $this->response(array('status' => 'success', 'token' => $data['token']), 200);
                }
            } else {
                $this->response(array('status' => 'fail', 'message' => 'Password salah!'), 400);
            }
        } else {
            $this->response(array('status' => 'fail', 'message' => 'Akun tidak ditemukan!'), 400);
        }
    }
}
