<?php 


defined('BASEPATH') OR exit('No direct script access allowed');

class C_home extends CI_Controller {

    public function index()
    {


        // ambil data dari api "api/menu"
        

        $data = [
            'title' => 'Dashboard',
            'content' => 'app/v_dashboard',
        ];

        $this->load->view('app/templates/template', $data, FALSE);
        
    }

}

/* End of file C_home.php */
