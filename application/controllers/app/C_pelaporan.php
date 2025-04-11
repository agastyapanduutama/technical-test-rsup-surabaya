<?php


defined('BASEPATH') or exit('No direct script access allowed');

class C_pelaporan extends CI_Controller
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
            redirect('app/pelaporan', 'refresh');
        }
    }


    public function index()
    {

        // $this->req->print($_SESSION);

        $data = [
            'title'     => 'Data Pelaporan',
            'content'   => 'app/pelaporan/index',
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
            'url'    => 'insiden',
        ];

        echo $getData = $this->req->req_get_data($dataRequest);
    }

    public function add()
    {
        $this->check_akses(1);

        $data = [
            'title'     => 'Tambah Pelaporan',
            'content'   => 'app/pelaporan/add',
        ];

        $this->load->view('app/templates/template', $data, FALSE);
    }

    public function insert()
    {

        $this->check_akses(1);


        $dataRequest = [
            'url'    => 'insiden',
            'data'   => [
                'nama_insiden'    => $this->input->post('nama_insiden'),
                'lokasi'          => $this->input->post('lokasi'),
                'waktu_insiden'   => $this->input->post('waktu_insiden'),
                'keterangan'      => $this->input->post('keterangan'),
                'status_insiden'  => $this->input->post('status_insiden'),
                'id_user'         => $this->input->post('id_user'),
            ],
        ];
        $kirimData = $this->req->req_post_data($dataRequest);

        // $this->req->print($kirimData);




        if(isset($_FILES['berkas']['name'])){
            $dir = 'assets/uploads/berkas_insiden/';

            if (!is_dir($dir)) {
                mkdir($dir, 0777, true);
            }

            $berkas = array(
                'path'      => $dir,
                'encrypt'   => TRUE,
                'type'      => 'custom',
                'file'      => 'berkas'
            );

            $berkas = $this->req->upload($berkas);

            // unggah berkas
            $dataRequestBerkas = [
                'url'    => 'insidenBerkas',
                'data'   => [
                    'id_insiden'    => json_decode($kirimData)->data->id,
                    'nama_berkas'   => $_FILES['berkas']['name'],
                    'berkas'        => (isset($berkas['data']['file_name'])) ? @$berkas['data']['file_name'] : false,
                    'status'        => $this->input->post('status'),
                ],
            ];
            $kirimDataBerkas = $this->req->req_post_data($dataRequestBerkas);
        }

        // $this->req->print($kirimDataBerkas);
        



        if (json_decode($kirimData)->status == 'success') {
            $this->session->set_flashdata('success', 'Data Berhasil Ditambahkan');
            redirect('app/pelaporan/detail/' . json_decode($kirimData)->data->id, 'refresh', 'refresh');
        } else {
            $this->session->set_flashdata('error', 'Data Gagal Ditambahkan');
            redirect('app/pelaporan/add', 'refresh');
        }
    }

    public function edit($iddata)
    {
        $this->check_akses(1);
        $dataRequest = [
            'url'    => 'insiden/' . $iddata,
        ];

        $getData = $this->req->req_get_data($dataRequest);

        // $this->req->print($getData);


        $dataRequestBerkas = [
            'url'    => 'insidenberkas/' . $iddata,
        ];

        $getDataBerkas = $this->req->req_get_data($dataRequestBerkas);

        // $this->req->print($getDataBerkas);

        if(json_decode($getDataBerkas)->status == 'fail'){
            $berkas = '';
        }else{
            $berkas = json_decode($getDataBerkas)[0];
        }

        
        $data = [
            'data'      => json_decode($getData)[0],
            'berkas'    => $berkas,
            'title'     => 'Edit Pelaporan',
            'content'   => 'app/pelaporan/edit',
        ];
        // $this->req->print($data);


        // $this->req->print($data);


        $this->load->view('app/templates/template', $data, FALSE);
    }

    public function detail($iddata)
    {
        $this->check_akses(1);
        $dataRequest = [
            'url'    => 'insiden/' . $iddata,
        ];

        $getData = $this->req->req_get_data($dataRequest);


        $dataRequestBerkas = [
            'url'    => 'insidenberkas/' . $iddata,
        ];

        $getDataBerkas = $this->req->req_get_data($dataRequestBerkas);
        if (json_decode($getDataBerkas)->status == 'fail') {
            $berkas = '';
        } else {
            $berkas = json_decode($getDataBerkas)[0];
        }

        // $this->req->print($berkas);


        $data = [
            'berkas'    => $berkas,
            'data'      => json_decode($getData)[0],
            'title'     => 'Edit Pelaporan',
            'content'   => 'app/pelaporan/detail',
        ];
        // $this->req->print($data);


        // $this->req->print($data);


        $this->load->view('app/templates/template', $data, FALSE);
    }

    public function update($iddata)
    {
        $this->check_akses(1);
        $dataRequest = [
            'url'    => 'insiden/' . $iddata,
            'data'   => [
                'nama_insiden'    => $this->input->post('nama_insiden'),
                'lokasi'          => $this->input->post('lokasi'),
                'waktu_insiden'   => $this->input->post('waktu_insiden'),
                'keterangan'      => $this->input->post('keterangan'),
                'status_insiden'  => $this->input->post('status_insiden'),
                'id_user'         => $this->input->post('id_user'),
            ],
        ];

        
        $updateData = $this->req->req_put_data($dataRequest);



        if (isset($_FILES['berkas']['name'])) {
            $dir = 'assets/uploads/berkas_insiden/';

            if (!is_dir($dir)) {
                mkdir($dir, 0777, true);
            }

            $berkas = array(
                'path'      => $dir,
                'encrypt'   => TRUE,
                'type'      => 'custom',
                'file'      => 'berkas'
            );

            $berkas = $this->req->upload($berkas);

            $iddataberkas = $this->input->post('iddataberkas');

            // unggah berkas
            $dataRequestBerkas = [
                'url'    => 'insidenBerkas/' . $iddataberkas,
                'data'   => [
                    'id_insiden'    => $iddata,
                    'nama_berkas'   => $_FILES['berkas']['name'],
                    'berkas'        => (isset($berkas['data']['file_name'])) ? @$berkas['data']['file_name'] : false,
                    'status'        => $this->input->post('status'),
                ],
            ];
            $updateDataBerkas = $this->req->req_put_data($dataRequestBerkas);
        }



        if (json_decode($updateData)->status == 'success') {
            $this->session->set_flashdata('success', 'Data Berhasil Perbarui');
            redirect('app/pelaporan/detail/' . $iddata, 'refresh');
        } else {
            $this->session->set_flashdata('error', 'tidak ada perubahan data');
            redirect('app/pelaporan', 'refresh');
        }
    }

    public function delete($iddata)
    {
        $this->check_akses(1);
        $dataRequest = [
            'url'    => 'insiden/' . $iddata,
        ];
        echo $deleteData = $this->req->req_delete_data($dataRequest);
    }
}

/* End of file C_pelaporan.php */
