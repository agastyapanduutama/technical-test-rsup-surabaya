<?php

defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

use Restserver\Libraries\REST_Controller;

class Menu extends REST_Controller
{

    function __construct($config = 'rest')
    {
        parent::__construct($config);
        $this->load->model('app/M_menu', 'menu');
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

    function index_get($idmenu = null)
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

        $menu = $this->menu->getDataMenu($idmenu, $filterCustom);
        if(empty($menu)) {
            $this->response(array('status' => 'fail', 'message' => 'Data tidak ditemukan'), 404);
            return;
        }else{}
        $this->response($menu, 200);
    }

    function index_post()
    {

        // cek form validasi
        $this->form_validation->set_rules('link', 'link', 'required|trim');
        $this->form_validation->set_rules('nama_menu', 'Nama menu', 'required|trim|is_unique[t_menu.nama_menu]');
        $this->form_validation->set_rules('keterangan', 'Keterangan', 'trim');
        $this->form_validation->set_rules('status', 'status', 'trim');
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
            'link'              => $this->post('link'),
            'nama_menu'         => $this->post('nama_menu'),
            'keterangan'        => $this->post('keterangan'),
            'status'            => $this->post('status'),
        );

        // cek apakah link sudah ada atau belum
        $cek = $this->menu->checkUDataByNameMenu($data['nama_menu']);
        if ($cek) {
            $this->response(array('status' => 'fail', 'message' => 'Nama Menu sudah digunakan'), 400);
            return;
        }
        
        $insert = $this->menu->insertDataMenu($data);
        if ($insert == true) {
            $data['id'] = $this->db->insert_id();
            $message = [
                'status'    => 'success',
                'message'   => 'Data Menu berhasil ditambahkan',
                'data'      => $data,
            ];


            $this->response($message, 200);
        } else {
            $this->response(array('status' => 'fail', 502));
        }
    }



    function index_put($idmenu)
    {
        $menu = $this->menu->getDataMenu($idmenu);
        if (empty($menu)) {
            $this->response(array('status' => 'fail', 'message' => 'Data tidak ditemukan'), 404);
            return;
        }
        
        // cek form validasi
        $data = array(
            'link'          => $this->put('link'),
            'nama_menu'     => $this->put('nama_menu'),
            'keterangan'    => $this->put('keterangan'),
            'status'        => $this->put('status'),
        );

        $this->form_validation->set_data($data);

        $this->form_validation->set_rules('link', 'link', 'required|trim');
        $this->form_validation->set_rules('nama_menu', 'nama_menu', 'required|trim|callback_check_nama_menu[' . $idmenu . ']');
        $this->form_validation->set_rules('keterangan', 'keterangan', 'trim');
        $this->form_validation->set_message('required', '%s tidak boleh kosong');
        $this->form_validation->set_message('is_unique', '%s sudah digunakan');
        $this->form_validation->set_message('min_length', '%s minimal 6 karakter');
        $this->form_validation->set_error_delimiters('', '');

        if ($this->form_validation->run() == false) {
            $this->response(array('status' => 'fail', 'message' => validation_errors()), 400);
            return;
        }

        $update = $this->menu->updateDataMenu($idmenu, $data);
        if ($update == true) {
            $data['id'] = $idmenu;
            $message = [
                'status'    => 'success',
                'message'   => 'Data Menu berhasil diperbarui',
                'data'      => $data,
            ];

            $this->response($message, 200);
        } else {
            $this->response(array('status' => 'fail', 502));
        }

    }

    function check_nama_menu($link,$idmenu)
    {
        $menu = $this->menu->checkUDataByNameMenu($link, $idmenu);
        if ($menu > 0) {
            $this->form_validation->set_message('check_nama_menu', 'Nama Menu Sudah Digunakan');
            return FALSE;
        } else {
            return TRUE;
        }
    }


    function index_delete($idmenu)
    {

        $menu = $this->menu->getDataMenu($idmenu);
        if (empty($menu)) {
            $this->response(array('status' => 'fail', 'message' => 'Data tidak ditemukan'), 404);
            return;
        }
        $delete = $this->menu->deleteDataMenu($idmenu);
        if ($delete == true) {
            $message = [
                'status' => 'success',
                'message' => 'Data Menu berhasil dihapus',
            ];
            $this->response($message, 200);
        } else {
            $this->response(array('status' => 'fail', 502));
        }
    }
}
