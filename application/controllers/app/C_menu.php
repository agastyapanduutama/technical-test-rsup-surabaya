<?php 


defined('BASEPATH') OR exit('No direct script access allowed');

class C_menu extends CI_Controller {


    
    public function __construct()
    {
        parent::__construct();
        if(!$this->session->userdata('token')) {
            $this->session->set_flashdata('warning', 'Anda Perlu masuk terlebih dahulu');
            redirect('login', 'refresh');
        }


    }

    private function check_akses($level)
    {
        if ($this->session->userdata('akses') == $level) {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses untuk melakukan aksi ini');
            redirect('app/menu', 'refresh');
        }
    }
    

    public function index()
    {

        // $this->req->print($_SESSION);

        $data = [
            'title'     => 'Data Menu',
            'content'   => 'app/menu/index',
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
            'url'    => 'menu',
        ];

        echo $getData = $this->req->req_get_data($dataRequest);

    }

    public function add()
    {
        $this->check_akses(1);

        $data = [
            'title'     => 'Tambah Menu',
            'content'   => 'app/menu/add',
        ];

        $this->load->view('app/templates/template', $data, FALSE);
    }

    public function insert()
    {

        $this->check_akses(1);
        // $this->req->print($_POST);
        $dataRequest = [
            'url'    => 'menu',
            'data'   => [
                'nama_menu' => $this->input->post('nama_menu'),
                'link'      => $this->input->post('link'),
                'keterangan'=> $this->input->post('keterangan'),
                'status'    => $this->input->post('status'),
            ],
        ];
        $kirimData = $this->req->req_post_data($dataRequest);


        if(json_decode($kirimData)->status == 'success'){
            $this->session->set_flashdata('success', 'Data Berhasil Ditambahkan');
            redirect('app/menu', 'refresh');
        }else{
            $this->session->set_flashdata('error', 'Data Gagal Ditambahkan');
            redirect('app/menu/add', 'refresh');
        }

    }

    public function edit($iddata)
    {

        $this->check_akses(1);
        $dataRequest = [
            'url'    => 'menu/' . $iddata,
        ];

        $getData = $this->req->req_get_data($dataRequest);
        $data = [
            'data'      => json_decode($getData)[0],
            'title'     => 'Edit Menu',
            'content'   => 'app/menu/edit',
        ];


        // $this->req->print($data);


        $this->load->view('app/templates/template', $data, FALSE);
    }

    public function update($iddata)
    {
        $this->check_akses(1);
        $dataRequest = [
            'url'    => 'menu/' . $iddata,
            'data'   => [
                'nama_menu' => $this->input->post('nama_menu'),
                'link'      => $this->input->post('link'),
                'keterangan'=> $this->input->post('keterangan'),
                'status'    => $this->input->post('status'),
            ],
        ];

        

        $updateData = $this->req->req_put_data($dataRequest);
        if (json_decode($updateData)->status == 'success') {
            $this->session->set_flashdata('success', 'Data Berhasil Perbarui');
        } else {
            $this->session->set_flashdata('error', 'tidak ada perubahan data');
        }
        redirect('app/menu', 'refresh');


    }

    public function delete($iddata)
    {
        $this->check_akses(1);
        $dataRequest = [
            'url'    => 'menu/' . $iddata,
        ];
        echo $deleteData = $this->req->req_delete_data($dataRequest);


    }

}

/* End of file C_menu.php */
