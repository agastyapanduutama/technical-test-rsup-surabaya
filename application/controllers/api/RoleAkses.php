<?php

defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

use Restserver\Libraries\REST_Controller;

class RoleAkses extends REST_Controller
{

    function __construct($config = 'rest')
    {
        parent::__construct($config);
        $this->load->model('app/M_roleakses', 'roleakses');
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

        
    }

    function index_get($idroleakses = null)
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

        $roleakses = $this->roleakses->getDataRoleAkses($idroleakses, $filterCustom);
        if (empty($roleakses)) {
            $this->response(array('status' => 'fail', 'message' => 'Data tidak ditemukan'), 404);
            return;
        } else {
        }
        $this->response($roleakses, 200);
    }

    function index_post()
    {

        // cek menu 
        $cekMenu = $this->roleakses->getDataUser($this->post('id_user'));
        if (empty($cekMenu)) {
            $this->response(array('status' => 'fail', 'message' => 'Data Menu tidak ditemukan'), 404);
            return;
        }

        
        // cek role 
        $cekRole = $this->roleakses->getDataRole($this->post('id_role'));
        if (empty($cekRole)) {
            $this->response(array('status' => 'fail', 'message' => 'Data Role tidak ditemukan'), 404);
            return;
        }
        

        // cek form validasi
        $this->form_validation->set_rules('id_role', 'id_role', 'required|trim');
        $this->form_validation->set_rules('id_user', 'id_user', 'required|trim');
        $this->form_validation->set_message('required', '%s tidak boleh kosong');
        $this->form_validation->set_error_delimiters('', '');

        if ($this->form_validation->run() == false) {
            $this->response(array('status' => 'fail', 'message' => validation_errors()), 400);
            return;
        }

        // data yang akan disimpan
        $data = array(
            'id_user'         => $this->post('id_user'),
            'id_role'         => $this->post('id_role'),
        );

        // cek apakah link sudah ada atau belum
        // $cek = $this->roleakses->checkUDataByAkses($data['id_user']);
        // if ($cek) {
        //     $this->response(array('status' => 'fail', 'message' => 'Data Hak Akses Role dan Menu sudah ada'), 400);
        //     return;
        // }

        // cek jika id_user tersebut sudah memiliki role
        $cekRoleUser = $this->roleakses->getDataRoleAksesByUser($this->post('id_user'));
        if ($cekRoleUser) {
            $this->response(array('status' => 'fail', 'message' => 'User sudah memiliki role'), 400);
            return;
        }

        $insert = $this->roleakses->insertDataRoleAkses($data);
        if ($insert == true) {
            $returnData = [
                'id'              => $this->db->insert_id(),
                'nama_user'       => $cekMenu->nama_user,
                'nama_role'       => $cekRole->nama_role,
                'id_user'         => $this->post('id_user'),
                'id_role'         => $this->post('id_role'),
            ];
            

            $message = [
                'status'    => 'success',
                'message'   => 'Data Role Akses berhasil ditambahkan',
                'data'      => $returnData,
            ];


            $this->response($message, 200);
        } else {
            $this->response(array('status' => 'fail', 502));
        }
    }

    function index_put($idroleakses)
    {

        $roleakses = $this->roleakses->getDataRoleAkses($idroleakses);
        if (empty($roleakses)) {
            $this->response(array('status' => 'fail', 'message' => 'Data tidak ditemukan'), 404);
            return;
        }

        // cek menu 
        $cekMenu = $this->roleakses->getDataUser($this->put('id_user'));
        if (empty($cekMenu)) {
            $this->response(array('status' => 'fail', 'message' => 'Data Menu tidak ditemukan'), 404);
            return;
        }


        // cek role 
        $cekRole = $this->roleakses->getDataRole($this->put('id_role'));
        if (empty($cekRole)) {
            $this->response(array('status' => 'fail', 'message' => 'Data Role tidak ditemukan'), 404);
            return;
        }

        $data = array(
            'id_user'         => $this->put('id_user'),
            'id_role'         => $this->put('id_role'),
        );

        $this->form_validation->set_data($data);


        // cek form validasi
        $this->form_validation->set_rules('id_role', 'id_role', 'required|trim');
        $this->form_validation->set_rules('id_user', 'id_user', 'required|trim');
        $this->form_validation->set_message('required', '%s tidak boleh kosong');
        $this->form_validation->set_error_delimiters('', '');

        if ($this->form_validation->run() == false) {
            $this->response(array('status' => 'fail', 'message' => validation_errors()), 400);
            return;
        }

        // data yang akan disimpan
        $data = array(
            'id_user'         => $this->put('id_user'),
            'id_role'         => $this->put('id_role'),
        );

        // cek apakah link sudah ada atau belum
        $cek = $this->roleakses->checkUDataByAkses($data, $idroleakses);
        if ($cek) {
            $this->response(array('status' => 'fail', 'message' => 'Data Hak Akses Role dan Menu sudah ada'), 400);
            return;
        }

        $update = $this->roleakses->updateDataRoleAkses($idroleakses, $data);
        if ($update == true) {
            $returnData = [
                'id'              => $idroleakses,
                'nama_user'       => $cekMenu->nama_user,
                'nama_role'       => $cekRole->nama_role,
                'id_user'         => $this->put('id_user'),
                'id_role'         => $this->put('id_role'),
            ];

            $message = [
                'status'    => 'success',
                'message'   => 'Data Role Akses berhasil diperbarui',
                'data'      => $returnData,
            ];


            $this->response($message, 200);
        } else {
            $this->response(array('status' => 'fail', 502));
        }
    }

    function check_nama_roleakses($link, $idroleakses)
    {
        $roleakses = $this->roleakses->checkUDataByNameRoleAkses($link, $idroleakses);
        if ($roleakses > 0) {
            $this->form_validation->set_message('check_nama_roleakses', 'Nama RoleAkses Sudah Digunakan');
            return FALSE;
        } else {
            return TRUE;
        }
    }


    function index_delete($idroleakses)
    {

        $roleakses = $this->roleakses->getDataRoleAkses($idroleakses);
        if (empty($roleakses)) {
            $this->response(array('status' => 'fail', 'message' => 'Data tidak ditemukan'), 404);
            return;
        }
        $delete = $this->roleakses->deleteDataRoleAkses($idroleakses);
        if ($delete == true) {
            $message = [
                'status' => 'success',
                'message' => 'Data RoleAkses berhasil dihapus',
            ];
            $this->response($message, 200);
        } else {
            $this->response(array('status' => 'fail', 502));
        }
    }
}
