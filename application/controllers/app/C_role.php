<?php


defined('BASEPATH') or exit('No direct script access allowed');

class C_role extends CI_Controller
{



    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('token')) {
            $this->session->set_flashdata('warning', 'Anda Perlu masuk terlebih dahulu');
            redirect('login', 'refresh');
        }
    }

    private function check_akses($level)
    {
        if ($this->session->userdata('akses') == $level) {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses untuk melakukan aksi ini');
            redirect('app/role', 'refresh');
        }
    }


    public function index()
    {

        // $this->req->print($_SESSION);

        $data = [
            'title'     => 'Data Role',
            'content'   => 'app/role/index',
        ];

        $this->load->view('app/templates/template', $data, FALSE);
    }

    public function data()
    {

        $filter = [
            'filter' => (!empty($this->input->get('filter')) ? $this->input->get('filter') : ''),
            'order'  => (!empty($this->input->get('order')) ? $this->input->get('filter') : ''),
            'offset' => (!empty($this->input->get('offset')) ? $this->input->get('filter') : ''),
            'limit'  => (!empty($this->input->get('limit')) ? $this->input->get('filter') : ''),
        ];

        $dataRequest = [
            'filter' => json_encode($filter),
            'url'    => 'role',
        ];

        echo $getData = $this->req->req_get_data($dataRequest);
    }

    public function add()
    {
        $this->check_akses(1);

        $data = [
            'title'     => 'Tambah Role',
            'content'   => 'app/role/add',
        ];

        $this->load->view('app/templates/template', $data, FALSE);
    }

    public function insert()
    {

        $this->check_akses(1);
        $dataRequest = [
            'url'    => 'role',
            'data'   => [
                'nama_role' => $this->input->post('nama_role'),
                'keterangan' => $this->input->post('keterangan'),
                'hak_akses'    => $this->input->post('hak_akses'),
            ],
        ];
        $kirimData = $this->req->req_post_data($dataRequest);
        // $this->req->print($_SESSION);


        if (json_decode($kirimData)->status == 'success') {
            $this->session->set_flashdata('success', 'Data Berhasil Ditambahkan');
            redirect('app/role', 'refresh');
        } else {
            $this->session->set_flashdata('error', json_decode($kirimData)->message);
            redirect('app/role/add', 'refresh');
        }
    }

    public function edit($iddata)
    {

        $this->check_akses(1);
        $dataRequest = [
            'url'    => 'role/' . $iddata,
        ];

        $getData = $this->req->req_get_data($dataRequest);
        $data = [
            'data'      => json_decode($getData)[0],
            'title'     => 'Edit Role',
            'content'   => 'app/role/edit',
        ];


        // $this->req->print($data);


        $this->load->view('app/templates/template', $data, FALSE);
    }

    public function update($iddata)
    {
        $this->check_akses(1);
        $dataRequest = [
            'url'    => 'role/' . $iddata,
            'data'   => [
                'nama_role' => $this->input->post('nama_role'),
                'keterangan' => $this->input->post('keterangan'),
                'hak_akses'    => $this->input->post('hak_akses'),
            ],
        ];



        $updateData = $this->req->req_put_data($dataRequest);
        if (json_decode($updateData)->status == 'success') {
            $this->session->set_flashdata('success', 'Data Berhasil Perbarui');
        } else {
            $this->session->set_flashdata('error', 'tidak ada perubahan data');
        }
        redirect('app/role', 'refresh');
    }

    public function delete($iddata)
    {
        $this->check_akses(1);
        $dataRequest = [
            'url'    => 'role/' . $iddata,
        ];
        echo $deleteData = $this->req->req_delete_data($dataRequest);
    }
}

/* End of file C_role.php */
