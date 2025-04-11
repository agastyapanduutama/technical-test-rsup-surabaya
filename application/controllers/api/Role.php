<?php

defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

use Restserver\Libraries\REST_Controller;

class Role extends REST_Controller
{

    function __construct($config = 'rest')
    {
        parent::__construct($config);
        $this->load->model('app/M_role', 'role');
        
        $token = $this->input->get_request_header('Authorization');
        
        $token = explode('-', $token, 2);
        $akses = base64_decode($token[1]);
        
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
    }

    function index_get($idrole = null)
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

        $role = $this->role->getDataRole($idrole, $filterCustom);
        if (empty($role)) {
            $this->response(array('status' => 'fail', 'message' => 'Data tidak ditemukan'), 404);
            return;
        } else {
        }
        $this->response($role, 200);
    }

    function index_post()
    {

        // cek form validasi
        $this->form_validation->set_rules('nama_role', 'Nama role', 'required|trim|is_unique[t_role.nama_role]');
        $this->form_validation->set_rules('keterangan', 'Keterangan', 'trim');
        $this->form_validation->set_rules('hak_akses', 'hak_akses', 'trim|required');
        $this->form_validation->set_message('required', '%s tidak boleh kosong');
        $this->form_validation->set_message('is_unique', '%s sudah digunakan');
        $this->form_validation->set_message('min_length', '%s minimal 6 karakter');
        $this->form_validation->set_error_delimiters('', '');


        // hak akses hanya menerima 1 -3
        $hak_akses = $this->post('hak_akses');
        if ($hak_akses < 1 || $hak_akses > 3) {
            $this->response(array('status' => 'fail', 'message' => 'Hak akses tidak valid'), 400);
            return;
        }

        if ($this->form_validation->run() == false) {
            $this->response(array('status' => 'fail', 'message' => validation_errors()), 400);
            return;
        }

        // data yang akan disimpan
        $data = array(
            'nama_role'         => $this->post('nama_role'),
            'keterangan'        => $this->post('keterangan'),
            'hak_akses'        => $this->post('hak_akses'),
        );

        $cek = $this->role->checkUDataByNameRole($data['nama_role']);
        if ($cek) {
            $this->response(array('status' => 'fail', 'message' => 'Nama Role sudah digunakan'), 400);
            return;
        }

        $insert = $this->role->insertDataRole($data);
        if ($insert == true) {
           
            $data['id'] = $this->db->insert_id();
            $message = [
                'status'    => 'success',
                'message'   => 'Data Role berhasil ditambahkan',
                'data'      => $data,
            ];


            $this->response($message, 200);
        } else {
            $this->response(array('status' => 'fail', 502));
        }
    }



    function index_put($idrole)
    {
        $role = $this->role->getDataRole($idrole);
        if (empty($role)) {
            $this->response(array('status' => 'fail', 'message' => 'Data tidak ditemukan'), 404);
            return;
        }

        // cek form validasi
        $data = array(
            'nama_role'     => $this->put('nama_role'),
            'keterangan'    => $this->put('keterangan'),
            'hak_akses'    => $this->put('hak_akses'),
        );


        // hak akses hanya menerima 1 -3
        $hak_akses = $this->put('hak_akses');
        if ($hak_akses < 1 || $hak_akses > 3) {
            $this->response(array('status' => 'fail', 'message' => 'Hak akses tidak valid'), 400);
            return;
        }

        $this->form_validation->set_data($data);

        $this->form_validation->set_rules('nama_role', 'nama_role', 'required|trim|callback_check_nama_role[' . $idrole . ']');
        $this->form_validation->set_rules('hak_akses', 'hak_akses', 'trim|required');
        $this->form_validation->set_rules('keterangan', 'keterangan', 'trim');
        $this->form_validation->set_message('required', '%s tidak boleh kosong');
        $this->form_validation->set_message('is_unique', '%s sudah digunakan');
        $this->form_validation->set_message('min_length', '%s minimal 6 karakter');
        $this->form_validation->set_error_delimiters('', '');

        if ($this->form_validation->run() == false) {
            $this->response(array('status' => 'fail', 'message' => validation_errors()), 400);
            return;
        }


        $update = $this->role->updateDataRole($idrole, $data);
        if ($update == true) {
            $data['id'] = $idrole;
            $message = [
                'status'    => 'success',
                'message'   => 'Data Role berhasil diperbarui',
                'data'      => $data,
            ];

            $this->response($message, 200);
        } else {
            $this->response(array('status' => 'fail', 502));
        }
    }

    function check_nama_role($link, $idrole)
    {
        $role = $this->role->checkUDataByNameRole($link, $idrole);
        if ($role > 0) {
            $this->form_validation->set_message('check_nama_role', 'Nama Role Sudah Digunakan');
            return FALSE;
        } else {
            return TRUE;
        }
    }


    function index_delete($idrole)
    {

        $role = $this->role->getDataRole($idrole);
        if (empty($role)) {
            $this->response(array('status' => 'fail', 'message' => 'Data tidak ditemukan'), 404);
            return;
        }
        $delete = $this->role->deleteDataRole($idrole);
        if ($delete == true) {
            $message = [
                'status' => 'success',
                'message' => 'Data Role berhasil dihapus',
            ];
            $this->response($message, 200);
        } else {
            $this->response(array('status' => 'fail', 502));
        }
    }
}
