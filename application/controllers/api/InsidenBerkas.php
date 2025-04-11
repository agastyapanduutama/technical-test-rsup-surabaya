<?php

defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

use Restserver\Libraries\REST_Controller;

class InsidenBerkas extends REST_Controller
{

    function __construct($config = 'rest')
    {
        parent::__construct($config);
        $this->load->model('app/M_insidenberkas', 'insidenberkas');
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

    function index_get($idinsiden = null, $idinsidenberkas = null)
    {
        error_reporting(0);
        if(empty($idinsiden)) {
            $this->response(array('status' => 'fail', 'message' => 'ID Insiden tidak ditemukan'), 404);
            return;
        }


        $insidenberkas = $this->insidenberkas->getDataInsidenBerkas($idinsidenberkas, $idinsiden);
        if (empty($insidenberkas)) {
            $this->response(array('status' => 'fail', 'message' => 'Data tidak ditemukan'), 404);
            return;
        } else {
        }
        $this->response($insidenberkas, 200);
    }

    function index_post()
    {


        // cek apakah id insiden ada
        $idinsiden = $this->post('id_insiden');

        // $this->req->print($_POST);

        $cek = $this->insidenberkas->getDataInsiden($idinsiden);
        if (empty($cek)) {
            $this->response(array('status' => 'fail', 'message' => 'ID Insiden tidak ditemukan'), 404);
            return;
        }


        $this->form_validation->set_rules('id_insiden', 'id_insiden', 'required|trim');
        $this->form_validation->set_rules('nama_berkas', 'nama_berkas', 'required|trim');
        $this->form_validation->set_rules('keterangan', 'Keterangan', 'trim');
        $this->form_validation->set_rules('status', 'status', 'trim');
        $this->form_validation->set_message('required', '%s tidak boleh kosong');
        $this->form_validation->set_message('min_length', '%s minimal 6 karakter');
        $this->form_validation->set_error_delimiters('', '');

        if ($this->form_validation->run() == false) {
            $this->response(array('status' => 'fail', 'message' => validation_errors()), 400);
            return;
        }

        if(!empty($_FILES['berkas']['name'])){
            $dir = 'assets/uploads/berkas_insiden/';

            if (!is_dir($dir)) {
                mkdir($dir, 0777, true);
            }

            $berkas = array(
                'path'      => $dir,
                'encrypt'   => TRUE,
                'type'      => 'doc',
                'file'      => 'berkas'
            );

            $berkas = $this->req->upload($berkas);
            $berkas = (isset($berkas['data']['file_name'])) ? @$berkas['data']['file_name'] : false;
        }else{
            $berkas = $this->post('berkas');
        }
        

        // data yang akan disimpan
        $data = array(
            'id_insiden'        => $this->post('id_insiden'),
            'nama_berkas'       => $this->post('nama_berkas'),
            'berkas'            => $berkas,
            'keterangan'        => $this->post('keterangan'),
            'status'            => 0,
        );

        $insert = $this->insidenberkas->insertDataInsidenBerkas($data);
        if ($insert == true) {
            $data['id'] = $this->db->insert_id();
            $message = [
                'status'    => 'success',
                'message'   => 'Data InsidenBerkas berhasil ditambahkan',
                'data'      => $data,
            ];

            $this->response($message, 200);
        } else {
            $this->response(array('status' => 'fail', 502));
        }
    }



    function index_put($idinsidenberkas)
    {
        $insidenberkas = $this->insidenberkas->getDataInsidenBerkas($idinsidenberkas);
        if (empty($insidenberkas)) {
            $this->response(array('status' => 'fail', 'message' => 'Data tidak ditemukan'), 404);
            return;
        }

        if (!empty($_FILES['berkas']['name'])) {
            $dir = 'assets/uploads/berkas_insiden/';

            if (!is_dir($dir)) {
                mkdir($dir, 0777, true);
            }

            $berkas = array(
                'path'      => $dir,
                'encrypt'   => TRUE,
                'type'      => 'doc',
                'file'      => 'berkas'
            );

            $berkas = $this->req->upload($berkas);
            $berkas = (isset($berkas['data']['file_name'])) ? @$berkas['data']['file_name'] : false;
        } else {
            $berkas = $this->put('berkas');
        }
        // cek form validasi
        $data = array(
            'id_insiden'        => $this->put('id_insiden'),
            'nama_berkas'       => $this->put('nama_berkas'),
            'berkas'            => $berkas,
            'keterangan'        => $this->put('keterangan'),
            'status'            => 0,
        );

        $data = $this->req->all($data);

        $this->form_validation->set_data($data);

        // $this->form_validation->set_rules('id_insiden', 'id_insiden', 'required|trim');
        $this->form_validation->set_rules('nama_berkas', 'nama_berkas', 'required|trim');
        $this->form_validation->set_rules('keterangan', 'Keterangan', 'trim');
        $this->form_validation->set_rules('status', 'status', 'trim');
        $this->form_validation->set_message('required', '%s tidak boleh kosong');
        $this->form_validation->set_message('min_length', '%s minimal 6 karakter');
        $this->form_validation->set_error_delimiters('', '');

        if ($this->form_validation->run() == false) {
            $this->response(array('status' => 'fail', 'message' => validation_errors()), 400);
            return;
        }

        $update = $this->insidenberkas->updateDataInsidenBerkas($idinsidenberkas, $data);
        if ($update == true) {
            $data['id'] = $idinsidenberkas;
            $message = [
                'status'    => 'success',
                'message'   => 'Data Insiden Berkas berhasil diperbarui',
                'data'      => $data,
            ];

            $this->response($message, 200);
        } else {
            $this->response(array('status' => 'fail', 502));
        }
    }


    function index_delete($idinsidenberkas)
    {

        $insidenberkas = $this->insidenberkas->getDataInsidenBerkas($idinsidenberkas);
        if (empty($insidenberkas)) {
            $this->response(array('status' => 'fail', 'message' => 'Data tidak ditemukan'), 404);
            return;
        }
        $delete = $this->insidenberkas->deleteDataInsidenBerkas($idinsidenberkas);
        if ($delete == true) {
            $message = [
                'status' => 'success',
                'message' => 'Data InsidenBerkas berhasil dihapus',
            ];
            $this->response($message, 200);
        } else {
            $this->response(array('status' => 'fail', 502));
        }
    }
}
