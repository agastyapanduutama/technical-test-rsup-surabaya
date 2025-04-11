<?php

defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

use Restserver\Libraries\REST_Controller;

class Insiden extends REST_Controller
{

    function __construct($config = 'rest')
    {
        parent::__construct($config);
        $this->load->model('app/M_insiden', 'insiden');
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

    function index_get($idinsiden = null)
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

        $insiden = $this->insiden->getDataInsiden($idinsiden, $filterCustom);
        if (empty($insiden)) {
            $this->response(array('status' => 'fail', 'message' => 'Data tidak ditemukan'), 404);
            return;
        } else {
        }
        $this->response($insiden, 200);
    }

    function index_post()
    {

        // $this->req->print($_POST);

        $this->form_validation->set_rules('nama_insiden', 'nama_insiden', 'required|trim');
        $this->form_validation->set_rules('lokasi', 'lokasi', 'required|trim');
        $this->form_validation->set_rules('waktu_insiden', 'waktu_insiden', 'required|trim');
        $this->form_validation->set_rules('keterangan', 'Keterangan', 'trim');
        $this->form_validation->set_rules('status', 'status', 'trim');
        $this->form_validation->set_message('required', '%s tidak boleh kosong');
        $this->form_validation->set_message('min_length', '%s minimal 6 karakter');
        $this->form_validation->set_error_delimiters('', '');

        if ($this->form_validation->run() == false) {
            $this->response(array('status' => 'fail', 'message' => validation_errors()), 400);
            return;
        }

        // data yang akan disimpan
        $data = array(
            'id_user'                   => $this->post('id_user'),
            'nama_insiden'              => $this->post('nama_insiden'),
            'lokasi'                    => $this->post('lokasi'),
            'keterangan'                => $this->post('keterangan'),
            'waktu_insiden'             => $this->post('waktu_insiden'),
            'status_insiden'            => (empty($this->post('status_insiden'))) ? '0' : $this->put('status_insiden'),
        );

        // $this->req->print($data);

        $insert = $this->insiden->insertDataInsiden($data);
        if ($insert == true) {
            $data['id'] = $this->db->insert_id();
            $message = [
                'status'    => 'success',
                'message'   => 'Data Insiden berhasil ditambahkan',
                'data'      => $data,
            ];

            $this->response($message, 200);
        } else {
            $this->response(array('status' => 'fail', 502));
        }
    }



    function index_put($idinsiden)
    {
        $insiden = $this->insiden->getDataInsiden($idinsiden);
        if (empty($insiden)) {
            $this->response(array('status' => 'fail', 'message' => 'Data tidak ditemukan'), 404);
            return;
        }

        // cek form validasi
        $data = array(
            'id_user'                   => $this->put('id_user'),
            'nama_insiden'              => $this->put('nama_insiden'),
            'lokasi'                    => $this->put('lokasi'),
            'keterangan'                => $this->put('keterangan'),
            'waktu_insiden'             => $this->put('waktu_insiden'),
            'status_insiden'            => (empty($this->post('status_insiden'))) ? '0' : $this->put('status_insiden'),
        );

        $this->form_validation->set_data($data);

        $this->form_validation->set_rules('nama_insiden', 'nama_insiden', 'required|trim');
        $this->form_validation->set_rules('lokasi', 'lokasi', 'required|trim');
        $this->form_validation->set_rules('waktu_insiden', 'waktu_insiden', 'required|trim');
        $this->form_validation->set_rules('keterangan', 'Keterangan', 'trim');
        $this->form_validation->set_rules('status', 'status', 'trim');
        $this->form_validation->set_message('required', '%s tidak boleh kosong');
        $this->form_validation->set_message('min_length', '%s minimal 6 karakter');
        $this->form_validation->set_error_delimiters('', '');

        if ($this->form_validation->run() == false) {
            $this->response(array('status' => 'fail', 'message' => validation_errors()), 400);
            return;
        }

      

        $update = $this->insiden->updateDataInsiden($idinsiden, $data);
        if ($update == true) {
            $data['id'] = $idinsiden;
            $message = [
                'status'    => 'success',
                'message'   => 'Data Insiden berhasil diperbarui',
                'data'      => $data,
            ];

            $this->response($message, 200);
        } else {
            $this->response(array('status' => 'fail', 502));
        }
    }


    function index_delete($idinsiden)
    {

        $insiden = $this->insiden->getDataInsiden($idinsiden);
        if (empty($insiden)) {
            $this->response(array('status' => 'fail', 'message' => 'Data tidak ditemukan'), 404);
            return;
        }
        $delete = $this->insiden->deleteDataInsiden($idinsiden);
        if ($delete == true) {
            $message = [
                'status' => 'success',
                'message' => 'Data Insiden berhasil dihapus',
            ];
            $this->response($message, 200);
        } else {
            $this->response(array('status' => 'fail', 502));
        }
    }
}
