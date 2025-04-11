<?php 


defined('BASEPATH') OR exit('No direct script access allowed');

class C_login extends CI_Controller {

    
    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_login', 'login');
    }
    

    public function index()
    {
        show_404();   
    }

    public function login()
    {

        // cek apakah sesi cookie tersimpan atau tidak jika tersimpan maka otomatis masuk
        if(isset($_COOKIE['auth_cookie'])){
            $cookie = $_COOKIE['auth_cookie'];
            // cek data user
            $user = $this->login->getUserByCookie($cookie);
            if($user) {
                // jika user non aktif
                if($user->status == 0) {
                    $this->session->set_flashdata('error', 'Akun anda tidak aktif silakan hubungi administrator!');
                    redirect('login');
                }else{
                    $akses = $this->login->getUserRole($user->id);
                    $token = bin2hex(random_bytes(32));
                    $data = array(
                        'token' => $token . '-' . base64_encode($akses->hak_akses),
                        'id_user' => $user->id,
                        'expired_at' => date('Y-m-d H:i:s', strtotime('+1 hour'))
                    );
                    $this->db->insert('token', $data);

                    $sessionData = [
                        'token'     => $data['token'],
                        'id_user'   => $user->id,
                        'akses'     => $akses->hak_akses,
                        'username'  => $user->username,
                        'nama_user' => $user->nama_user,
                        'status'    => $user->status,
                    ];
                    $this->session->set_userdata($sessionData);
                    redirect('app/dashboard');
                }
            }
        // jika cookie tidak ada dan user tidak ada
        }else{
            $data = [
                'title' => 'Masuk Aplikasi',
            ];
    
            $this->load->view('app/v_login', $data, FALSE);
        }

    }

    public function aksi()
    {


        // $this->req->print($_POST);
        // cek validasi form login
        $this->load->library('form_validation');
        $this->form_validation->set_rules('username', 'Username', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('login');
        }

        // cek apakah captcha sesuai atau tidak
        $captcha = $this->input->post('g-recaptcha-response');
        $secretKey = getenv('SECRET_KEY_GOOGLE_CAPTCHA');
        $verify = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$secretKey}&response={$captcha}");
        $captchaSuccess = json_decode($verify);

        if (!$captchaSuccess->success) {
            $this->session->set_flashdata('error', 'Captcha tidak valid! Silakan coba lagi.');
            redirect('login');
        }


        // cek apakah user tersebut ada atau tidak
        $username = $this->input->post('username', TRUE);
        $user = $this->login->getUserByUsername($username);

        // verify password
        if ($user) {
            if (password_verify($this->input->post('password', TRUE), $user->password)) {
                // apakah user aktif atau tidak
                if($user->status == 0) {
                    $this->session->set_flashdata('error', 'Akun anda tidak aktif!');
                    redirect('login');
                }else{
                    // set session data

                    $akses = $this->login->getUserRole($user->id);
                    $token = bin2hex(random_bytes(32));
                    $data = array(
                        'token' => $token . '-' . base64_encode($akses->hak_akses),
                        'id_user' => $user->id,
                        'expired_at' => date('Y-m-d H:i:s', strtotime('+1 hour'))
                    );
                    $this->db->insert('token', $data);

                    $sessionData = [
                        'token'     => $data['token'],
                        'id_user'   => $user->id,
                        'akses'     => $akses->hak_akses,
                        'username'  => $user->username,
                        'nama_user' => $user->nama_user,
                        'status'    => $user->status,
                    ];
                    $this->session->set_userdata($sessionData);
                    if(isset($_POST['remember'])) {
                        $this->input->set_cookie('auth_cookie', md5($_SESSION['id_user']), strtotime('+1 month'));
                    }
                    redirect('app/dashboard');
                }

            } else {
                $this->session->set_flashdata('error', 'Password salah!');
                redirect('login');
            }
        } else {
            $this->session->set_flashdata('error', 'Akun tidak ditemukan!');
            redirect('login');
        }
        
    }

    public function logout()
    {
        $this->load->helper('cookie');
        // remove token auth_cookie
        if(isset($_COOKIE['auth_cookie'])) {
            delete_cookie('auth_cookie');
        }
        // remove token from database
        $this->db->where('token', $this->session->userdata('token'));
        $this->db->delete('token');

        $this->session->sess_destroy();
        redirect('login');
    }

}

/* End of file C_login.php */
