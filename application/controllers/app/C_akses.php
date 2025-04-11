<?php


defined('BASEPATH') or exit('No direct script access allowed');

class C_akses extends CI_Controller
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
            redirect('app/akses', 'refresh');
        }
    }


    public function index()
    {

        // $this->req->print($_SESSION);

        $data = [
            'title'     => 'Data Akses',
            'content'   => 'app/akses/index',
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
            'url'    => 'roleAkses',
        ];

        echo $getData = $this->req->req_get_data($dataRequest);
    }

    public function add()
    {
        $this->check_akses(1);

        $role = $this->db->get('t_role')->result();
        $user = $this->db->get_where('t_user', ['status' => 1])->result();
        

        $data = [
            'user'      => $user,
            'role'      => $role,
            'title'     => 'Tambah Akses',
            'content'   => 'app/akses/add',
        ];

        $this->load->view('app/templates/template', $data, FALSE);
    }

    public function insert()
    {

        $this->check_akses(1);
        $dataRequest = [
            'url'    => 'roleAkses',
            'data'   => [
                'id_role' => $this->input->post('id_role'),
                'id_user' => $this->input->post('id_user'),
            ],
        ];
        $kirimData = $this->req->req_post_data($dataRequest);
        // $this->req->print($kirimData);

        if (json_decode($kirimData)->status == 'success') {
            $this->session->set_flashdata('success', 'Data Berhasil Ditambahkan');
            redirect('app/akses', 'refresh');
        } else {
            $this->session->set_flashdata('error', json_decode($kirimData)->message);
            redirect('app/akses/add', 'refresh');
        }
    }

    public function edit($iddata)
    {

        $this->check_akses(1);

        $role = $this->db->get('t_role')->result();
        $user = $this->db->get_where('t_user', ['status' => 1])->result();

        $dataRequest = [
            'url'    => 'roleAkses/' . $iddata,
        ];

        $getData = $this->req->req_get_data($dataRequest);
        $data = [
            'user'      => $user,
            'role'      => $role,
            'data'      => json_decode($getData)[0],
            'title'     => 'Edit Akses',
            'content'   => 'app/akses/edit',
        ];


        // $this->req->print($data);


        $this->load->view('app/templates/template', $data, FALSE);
    }

    public function update($iddata)
    {
        $this->check_akses(1);
        $dataRequest = [
            'url'    => 'roleAkses/' . $iddata,
            'data'   => [
                'id_role' => $this->input->post('id_role'),
                'id_user' => $this->input->post('id_user'),
            ],
        ];



        $updateData = $this->req->req_put_data($dataRequest);

        // $this->req->print($updateData);

        if (json_decode($updateData)->status == 'success') {
            $this->session->set_flashdata('success', 'Data Berhasil Perbarui');
        } else {
            $this->session->set_flashdata('error', 'tidak ada perubahan data');
        }
        redirect('app/akses', 'refresh');
    }

    public function delete($iddata)
    {
        $this->check_akses(1);
        $dataRequest = [
            'url'    => 'roleAkses/' . $iddata,
        ];
        echo $deleteData = $this->req->req_delete_data($dataRequest);
    }
}

/* End of file C_akses.php */
