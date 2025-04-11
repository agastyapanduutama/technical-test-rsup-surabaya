<?php

defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

use Restserver\Libraries\REST_Controller;

class User extends REST_Controller
{

    function __construct($config = 'rest')
    {
        parent::__construct($config);
        $this->load->model('app/M_user', 'user');
        $token = $this->input->get_request_header('Authorization');


        $token = explode('-', $token, 2);
        $akses = base64_decode($token[1]);
        $token = $token[0];

        $tokenfull = $this->input->get_request_header('Authorization');

        if (!$this->req->verify_token($tokenfull)) {
            $this->response(array('status' => 'fail', 'message' => 'Token tidak valid'), 401);
            exit;
        }


        $method = $this->input->method();
        // cek hak akses
        if ($akses == 1 && $method != 'get') {
            $this->response(array('status' => 'fail', 'message' => 'Hak akses tidak valid'), 403);
            exit;
        }

        // jika hak akses adalah 1 maka bisa get saja
        // jika hak akses adalah 2 maka bisa get, post, put, delete
        // jika hak akses adalah 3 maka bisa get, post, put, delete
        
    }

    function index_get($iduser = null)
    {

        error_reporting(0);
        $input_data = json_decode($this->input->raw_input_stream, true);

        // custom
        $filterCustom = [
            'filter'  => $input_data['filter'],
            'order'   => $input_data['order'],
            'limit'   => $input_data['limit'],
            'offset'  => $input_data['offset'],
        ];



        $user = $this->user->getDataUser($iduser, $filterCustom);
        if(empty($user)) {
            $this->response(array('status' => 'fail', 'message' => 'Data tidak ditemukan'), 404);
            return;
        }else{}
        $this->response($user, 200);
    }

    function index_post()
    {

        // cek form validasi
        $this->form_validation->set_rules('username', 'Username', 'required|trim|is_unique[t_user.username]');
        $this->form_validation->set_rules('password', 'Password', 'required|trim|min_length[6]');
        $this->form_validation->set_rules('nama_user', 'Nama User', 'required|trim');
        $this->form_validation->set_message('required', '%s tidak boleh kosong');
        $this->form_validation->set_message('is_unique', '%s sudah digunakan');
        $this->form_validation->set_message('min_length', '%s minimal 6 karakter');
        $this->form_validation->set_error_delimiters('', '');

        if ($this->form_validation->run() == false) {
            $this->response(array('status' => 'fail', 'message' => validation_errors()), 400);
            return;
        }

        // data yang akan disimpan
        $data = array(
            'username'          => $this->post('username'),
            'password'          => password_hash($this->post('password'), PASSWORD_BCRYPT),
            'nama_user'         => $this->post('nama_user'),
            'keterangan'        => $this->post('keterangan'),
            'status'            => 1,
        );

        // cek apakah username sudah ada atau belum
        $cek = $this->user->checkUDataByUsername($data['username']);
        if ($cek) {
            $this->response(array('status' => 'fail', 'message' => 'Username sudah digunakan'), 400);
            return;
        }
        
        $insert = $this->user->insertDataUser($data);
        if ($insert == true) {
            // remove password from response data
            unset($data['password']);
            $data['id'] = $this->db->insert_id();

            $message = [
                'status'    => 'success',
                'message'   => 'Data Pengguna berhasil ditambahkan',
                'data'      => $data,
            ];


            $this->response($message, 200);
        } else {
            $this->response(array('status' => 'fail', 502));
        }
    }



    function index_put($iduser)
    {

        // cek apakah iduser ada
        $user = $this->user->getDataUser($iduser);
        if(empty($user)) {
            $this->response(array('status' => 'fail', 'message' => 'Data tidak ditemukan'), 404);
            return;
        }

        // cek form validasi
        $data = array(
            'username'      => $this->put('username'),
            'password'      => $this->put('password'),
            'nama_user'     => $this->put('nama_user'),
            'keterangan'    => $this->put('keterangan'),
            'status'        => $this->put('status'),
        );

        $this->form_validation->set_data($data);

        $this->form_validation->set_rules('username', 'Username', 'required|trim|callback_check_username[' . $iduser . ']');
        $this->form_validation->set_rules('password', 'Password', 'trim|min_length[6]');
        $this->form_validation->set_rules('nama_user', 'Nama User', 'required|trim');
        $this->form_validation->set_message('required', '%s tidak boleh kosong');
        $this->form_validation->set_message('is_unique', '%s sudah digunakan');
        $this->form_validation->set_message('min_length', '%s minimal 6 karakter');
        $this->form_validation->set_error_delimiters('', '');

        if ($this->form_validation->run() == false) {
            $this->response(array('status' => 'fail', 'message' => validation_errors()), 400);
            return;
        }

        if(!empty($this->put('password'))) {
            $data['password'] = password_hash($this->put('password'), PASSWORD_BCRYPT);
        } else {
            unset($data['password']);
        }

        $update = $this->user->updateDataUser($iduser, $data);
        if ($update == true) {
            // remove password from response data
            unset($data['password']);
            $data['id'] = $iduser;
            $message = [
                'status'    => 'success',
                'message'   => 'Data Pengguna berhasil diperbarui',
                'data'      => $data,
            ];

            $this->response($message, 200);
        } else {
            $this->response(array('status' => 'fail', 502));
        }

    }

    function check_username($username,$iduser)
    {
        $user = $this->user->checkUDataByUsername($username, $iduser);
        if ($user > 0) {
            $this->form_validation->set_message('check_username', 'Username Tidak tersedia');
            return FALSE;
        } else {
            return TRUE;
        }
    }


    function index_delete($iduser)
    {
        $user = $this->user->getDataUser($iduser);
        if (empty($user)) {
            $this->response(array('status' => 'fail', 'message' => 'Data tidak ditemukan'), 404);
            return;
        }
        
        $delete = $this->user->deleteDataUser($iduser);
        if ($delete == true) {
            $message = [
                'status' => 'success',
                'message' => 'Data Pengguna berhasil dihapus',
            ];
            $this->response($message, 200);
        } else {
            $this->response(array('status' => 'fail', 502));
        }
    }
}
