<?php
// custom request
// custom code
class Request extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        $this->uploadTypes = array(
            'doc'    => ['allowed_types' => 'pdf|docx|doc'],
            'all'    => ['allowed_types' => '*'],
            'img'    => ['allowed_types' => 'jpg|jpeg|png|jpg'],
            'html'   => ['allowed_types' => 'html'],
            'custom' => ['allowed_types' => 'pdf|doc|docx|xls|xlsx|jpg|jpeg|png|ppt|pptx']
        );
    }


    function decAcak($id)
    {
        return array('md5(id)' => $id);
    }

    function encAcak($key)
    {
        return "md5($key)";
    }

    function acak($text)
    {
        return md5($text);
    }
    
    public function flash()
    {
         if ($this->session->flashdata('warning')) {
            echo '<div class="alert alert-warning">';
            echo $this->session->flashdata('warning');
            echo '</div>';
        }

        if ($this->session->flashdata('success')) {
            echo '<div class="alert alert-success">';
            echo $this->session->flashdata('success');
            echo '</div>';
        }

        if ($this->session->flashdata('error')) {
            echo '<div class="alert alert-danger">';
            echo $this->session->flashdata('error');
            echo '</div>';
        }
    }


    function print($array, $clear = true, $stop = true)
    {
        if ($clear == true) {
            ob_clean();
            echo "<pre>";
            echo print_r($array);
            echo "</pre>";
            exit(0);
        } else {
            echo "<pre>";
            echo print_r($array);
            echo "</pre>";
            if ($stop == true) {
                exit(0);
            }
        }
    }

    function cetak($array, $clear = true, $stop = true)
    {
        if ($clear == true) {
            // ob_clean();
            echo "<pre>";
            echo print_r($array);
            echo "</pre>";
            // exit(0);
        } else {
            echo "<pre>";
            echo print_r($array);
            echo "</pre>";
            // if ($stop == true) {
            //     exit(0);
            // }
        }
    }

    function json($array)
    {
        echo "<pre>";
        echo json_encode($array);
        echo "</pre>";
    }

    function input($input)
    {
        return htmlspecialchars(ltrim(rtrim($_POST[$input])));
    }

    function all($guarded = null)
    {
        $request = $_POST;
        foreach ($request as $key => $value) {
            $result[$key] = $this->input($key);
        }
        if ($guarded != null) {
            foreach ($guarded as $guard_ => $value) {
                if ($value == false) {
                    unset($request[$guard_]);
                } else {
                    unset($request[$guard_]);
                    $request[$guard_] = $value;
                }
            }
        }
        return $request;
    }


    function upload($data)
    {
        if (!file_exists($data['path'])) {
            mkdir($data['path'], 0777, true);
        }


        $maxSize = isset($data['max_size']) ? $data['max_size'] : 10000;


        $config = array(
            'upload_max_filesize' => $maxSize,  // Maksimal ukuran file
            'upload_path' => $data['path'],     // Path tempat file akan disimpan
            'encrypt_name' => $data['encrypt'], // Enkripsi nama file
            'max_size' => $maxSize,             // Ukuran maksimal file
        );

        // Konfigurasi tambahan jika ada parameter 'square'
        if (isset($data['square']) && $data['square']) {
            $squareCrop = true;
        } else {
            $squareCrop = false;
        }

        $config = array_merge($config, $this->uploadTypes[$data['type']]);

        // Load library upload dengan konfigurasi
        $this->load->library('upload', $config);

        // Lakukan proses upload
        $uploading = $this->upload->do_upload($data['file']) ? true : false;

        if (!$uploading) {
            return array(
                'message' => 'error',
                'data' => $this->upload->display_errors()
            );
        } else {
            // Ambil data file yang di-upload
            $upload_data = $this->upload->data(); 

            // Jika ada pengaturan square (crop to 1000x1000), lakukan crop
            if ($squareCrop) {
                $imageWidth = $upload_data['image_width'];
                $imageHeight = $upload_data['image_height'];

                // Tentukan ukuran dan posisi crop
                $cropSize = 2000;
                $x_axis = ($imageWidth / 2) - ($cropSize / 2); // Crop dari tengah-tengah
                $y_axis = ($imageHeight / 2) - ($cropSize / 2);

                $cropConfig['image_library'] = 'gd2'; 
                $cropConfig['source_image'] = $upload_data['full_path'];  // Path lengkap gambar yang di-upload
                $cropConfig['new_image'] = $upload_data['full_path'];     // Replace file yang ada
                $cropConfig['maintain_ratio'] = FALSE;  // Memaksa gambar menjadi 1000x1000 (tanpa menjaga rasio)
                $cropConfig['width'] = $cropSize;
                $cropConfig['height'] = $cropSize;
                $cropConfig['x_axis'] = $x_axis;  // Koordinat X untuk mulai crop
                $cropConfig['y_axis'] = $y_axis;  // Koordinat Y untuk mulai crop
                $cropConfig['quality'] = '90%';  // Kualitas gambar setelah crop

                // Load library image_lib untuk crop gambar
                $this->load->library('image_lib', $cropConfig);
                $this->image_lib->initialize($cropConfig);

                // Lakukan crop
                if (!$this->image_lib->crop()) {
                    return array(
                        'message' => 'error',
                        'data' => $this->image_lib->display_errors()
                    );
                }

                // Bersihkan konfigurasi library setelah digunakan
                $this->image_lib->clear();
            }

            // Return success dengan data upload
            return array(
                'message' => 'success',
                'data' => $upload_data
            );
        }


    }

    function upload_form($data)
    {
    
        $encrypt = (isset($data['encrypt']) == true) ? true : false;
        $fileName = (isset($data['fileName']) != '') ? $data['fileName'] : null;
        $customInput = (isset($data['customInput']) != '') ? $data['customInput'] : null;
        $maxSize = isset($data['max_size']) ? $data['max_size'] : 10000;
        
        if ($fileName) {
            $config = array(
                'upload_path' => './uploads/' . $data['path'],
                'file_name' => $data['fileName'],
                'max_size' => $maxSize
            );
        } else {
            $config = array(
                'upload_path' => './uploads/' . $data['path'],
                'encrypt_name' => $encrypt,
                'max_size' => $maxSize
            );
        }

        $config = array_merge($config, $this->uploadTypes[$data['type']]);
        $this->load->library('upload', $config);
        $uploading = $this->upload->do_upload($data['file']) ? true : false;
        if (!$uploading) {
            return $data_ = $this->all($customInput);
        } else {
            $data_ = $this->all($customInput);
            $upload_data = $this->upload->data();
            $result = array_merge($data_, [$data['file'] => $upload_data['file_name']]);
            // print_r($result);
            return $result;
        }
    }

    function upload_form_multi($data)
    {
        $fileName = [];
        // $this->print($data);
        $countfiles = count($_FILES[$data['file']]['name']);
        $maxSize = isset($data['max_size']) ? $data['max_size'] : 10000;
        $success = 0;

        if ($data['encrypt'] == true) {
            $config_ = array(
                'upload_path' => './uploads/' . $data['path'],
                'encrypt_name' => true,
                'max_size' => $maxSize
            );
        } else {
            $config_ = array(
                'upload_path' => './uploads/' . $data['path'],
                'encrypt_name' => false,
                'max_size' => $maxSize
            );
        }

        // echo $fileNameNa;

        $config = array_merge($config_, $this->uploadTypes[$data['type']]);

        $this->load->library('upload', $config);

        for ($i = 0; $i < $countfiles; $i++) {
            if (!empty($_FILES[$data['file']]['name'][$i])) {
                // echo $_FILES[$data['file']]['name'][$i];
                $fileNameNa = str_replace(["'", "`", ";", "^"], "", $_FILES[$data['file']]['name'][$i]);

                $_FILES['file']['name'] = $fileNameNa;
                $_FILES['file']['type'] = $_FILES[$data['file']]['type'][$i];
                $_FILES['file']['tmp_name'] = $_FILES[$data['file']]['tmp_name'][$i];
                $_FILES['file']['error'] = $_FILES[$data['file']]['error'][$i];
                $_FILES['file']['size'] = $_FILES[$data['file']]['size'][$i];

                $config['file_name'] = time() . "-" . $fileNameNa;

                $this->upload->initialize($config);

                // File upload
                $uploading = $this->upload->do_upload('file') ? true : false;

                if ($uploading) {
                    // Get data about the file
                    $success++;
                    $uploadData = $this->upload->data();
                    $fileName[] = $uploadData['file_name'];
                    $oriFile[] = $fileNameNa;
                } else {
                    return $this->upload->display_errors();
                }
            }
        }

        $fileNaGan = [];
        foreach ($fileName as $key) {
            $fileNaGan[] = $key;
        }

        $fileOriNaGan = [];
        foreach ($oriFile as $key) {
            $fileOriNaGan[] = $key;
        }

        // $fileNaGan = substr($fileNaGan, 0, strlen($fileNaGan) - 1);
        // $fileOriNaGan = substr($fileOriNaGan, 0, strlen($fileOriNaGan) - 1);
        // print_r($fileName);
        $custom = isset($data['customInput']) ? $data['customInput'] : null;
        return [
            'total' => $countfiles,
            'success' => $success,
            // 'data' => $this->all($custom),
            'file' => [
                'lampiran' => $fileNaGan,
                'oriFile'  => $fileOriNaGan
            ]
        ];

        // $fileNaGan = "";
        // foreach ($fileName as $key) {
        //     $fileNaGan .= "$key,";
        // }

        // $fileOriNaGan = "";
        // foreach ($oriFile as $key) {
        //     $fileOriNaGan .= "$key,";
        // }

        // $fileNaGan = substr($fileNaGan, 0, strlen($fileNaGan) - 1);
        // $fileOriNaGan = substr($fileOriNaGan, 0, strlen($fileOriNaGan) - 1);
        // // print_r($fileName);
        // $custom = isset($data['customInput']) ? $data['customInput'] : null;
        // return [
        //     'total' => $countfiles,
        //     'success' => $success,
        //     // 'data' => $this->all($custom),
        //     'file' => [
        //         'lampiran' => $fileNaGan,
        //         'oriFile'  => $fileOriNaGan
        //     ]
        // ];
    }


    function dateIndo($tanggal)
    {
        $bulan = array(
            1 =>   'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
        );
        $pecahkan = explode('-', $tanggal);

        return $pecahkan[2] . ' ' . $bulan[(int)$pecahkan[1]] . ' ' . $pecahkan[0];
    }

    function getBulan($bul)
    {
        // $bul = date('n');

        switch ($bul) {
            case 1:
                $bulan = "Januari";
                break;
            case 2:
                $bulan = "Febuari";
                break;
            case 3:
                $bulan = "Maret";
                break;
            case 4:
                $bulan = "April";
                break;
            case 5:
                $bulan = "Mei";
                break;
            case 6:
                $bulan = "Juni";
                break;
            case 7:
                $bulan = "Juli";
                break;
            case 8:
                $bulan = "Agustus";
                break;
            case 9:
                $bulan = "September";
                break;
            case 10:
                $bulan = "Oktober";
                break;
            case 11:
                $bulan = "November";
                break;
            case 12:
                $bulan = "Desember";
                break;
        }
        return $bulan;
    }

    public function getHari($hari)
    {
        // $hari = date("D");

        switch ($hari) {
            case 'Sun':
                $hari_ini = "Minggu";
                break;

            case 'Mon':
                $hari_ini = "Senin";
                break;

            case 'Tue':
                $hari_ini = "Selasa";
                break;

            case 'Wed':
                $hari_ini = "Rabu";
                break;

            case 'Thu':
                $hari_ini = "Kamis";
                break;

            case 'Fri':
                $hari_ini = "Jumat";
                break;

            case 'Sat':
                $hari_ini = "Sabtu";
                break;

            default:
                $hari_ini = "";
                break;
        }

        return $hari_ini;
    }

    public function secretKey()
    {
        $key = "put your secret key here";
        return $key;
    }

    function rupiah($angka)
    {
        $hasil_rupiah = "Rp " . number_format($angka, 0, ',', '.');
        return $hasil_rupiah;
    }


    function get_char(){
        return implode('',array_merge(range('a','z'),range(0,9),range('A','Z')));
    }

    function key_($i){
        $bc=array(
            '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'
    );
    
    if($i<62&&$i>=0)
        return $bc[$i];
    else
        return end($bc);
    }

    function ch($v,$k){
        $ada=false;
        $j=0;
        while(!$ada&&$j<62){
            $ada=$v==$k[$j];
            if(!$ada)$j++;
        }
        return $j;
    }

    // Belum dapat decryp tanda spesial seperti titik (.)
    function encr($v,$lq=false){
        $res='';
        $p=rand(0,61);
        $l=rand(0,61);
        $k=$this->key_($l);
        for($i=0;$i<strlen($v);$i++){
            $j=$this->ch($v[$i],$k);
            $j=($j+$p+$i)%62;
            $res.=$k[$j];
        }
        $res=$k[$p].$res;
        $k=$this->get_char();
        return $k[$l].$res;
    }

    function decr($v,$l=false){
        $k=$this->get_char();
        $j=$this->ch($v[0],$k);
        $k=$this->key_($j);
        $p=$this->ch($v[1],$k);
        $res='';
        for($i=2;$i<strlen($v);$i++){
            $j=$this->ch($v[$i],$k);
            $j=($j-$p-$i+2+(62*10000))%62;
            $res.=$k[$j];
        }
        return $res;
    }
    // 
    function enc_int($v,$l=50,$s='a'){
        $o=strlen($v);
        if($o<10) $v='0'.$o.$v;
        else $v=$o.$v;
        for($i=0;$i<$l-$o;$i++)
        $v.=$s;
        return $this->encr($v);
    }
    function dec_int($res,$s='a'){
        $res=$this->decr($res);
        $o=substr($res,0,2);
        $sf=str_replace($s,'',substr($res,$o+2));
        if($sf=='')
            return (int)substr($res,2,$o);
        else return false;
    }




        function remove_code(&$string)
        {
            $cari_huruf = array("'", '&amp;', '&copy;', '&crarr;', '&dArr;', '&deg;', '&divide;', '&eacute;', '&fnof;', '&frasl;', '&ge;', '&harr;', '&hArr;', '&hellip;', '&larr;', '&lArr;', '&ldquo;', '&le;', '&lowast;', '&lsquo;', '&mdash;', '&nbsp;', '&ndash;', '&ne;', '&plusmn;', '&prime;', '&Prime;', '&quot;', '&rarr;', '&rArr;', '&rdquo;', '&reg;', '&rsquo;', '&sect;', '&sum;', '&times;', '&uarr;', '&uArr;', '&lt;br /&gt;', '&lt;', '&gt;', '%', '&check;', '&raquo;', '&laquo;', "'");
            $huruf_baru = array("", '&', '©', '?', '?', '°', '÷', 'é', 'ƒ', '?', '?', '?', '?', '…', '?', '?', '“', '?', '?', '', '', '', '-', '?', '±', '?', '?', '"', '?', '?', '', '®', '', '§', '?', '×', '?', '?', '', '<', '>', "", "✓", ">>", "<<", "");
            return ucwords(strtoupper(str_ireplace($cari_huruf, $huruf_baru, $string)));
        }

        
        function enc_string($p){
            $dir=
                str_replace('/','004349',
                str_replace('.','105359',
                str_replace('-','206369',
                str_replace('_','307469',
                str_replace(' ','408419',
                str_replace('&','509429',
                str_replace('@','601449',
                str_replace('=','715429',
                str_replace('+','815429',
                str_replace('[','915329',
                str_replace(']','015578',
                str_replace('{','115418',
                str_replace('}','216428',
                str_replace('?','317618',
                str_replace('*','416618',
                str_replace('!','519628',
                str_replace(':','611368',
                str_replace('\\','724248',
                str_replace(',','825458',
                str_replace(' ','926138',
                str_replace('`','023718',
                str_replace('~','124558',
                str_replace('#','225458',
                str_replace('$','326258',
                str_replace('%','427728',
                str_replace('^','528538',
                str_replace('(','629548',
                str_replace(')','734238',
                str_replace(';','835428',
                str_replace('"','936678',
                str_replace('<','033817',
                str_replace('>','134667',
                str_replace('|','276443',
                str_replace('\'','784852',
            $p))))))))))))))))))))))))))))))))));
            return $this->encr($dir);
        }

        function dec_string($p){
            $dir=
                str_replace('004349','/',
                str_replace('105359','.',
                str_replace('206369','-',
                str_replace('307469','_',
                str_replace('408419',' ',
                str_replace('509429','&',
                str_replace('601449','@',
                str_replace('715429','=',
                str_replace('815429','+',
                str_replace('915329','[',
                str_replace('015578',']',
                str_replace('115418','{',
                str_replace('216428','}',
                str_replace('317618','?',
                str_replace('416618','*',
                str_replace('519628','!',
                str_replace('611368',':',
                str_replace('724248','\\',
                str_replace('825458',',',
                str_replace('926138',' ',
                str_replace('023718','`',
                str_replace('124558','~',
                str_replace('225458','#',
                str_replace('326258','$',
                str_replace('427728','%',
                str_replace('528538','^',
                str_replace('629548','(',
                str_replace('734238',')',
                str_replace('835428',';',
                str_replace('936678','"',
                str_replace('033817','<',
                str_replace('134667','>',
                str_replace('276443','|',
                str_replace('784852','\'',
            $this->decr($p)))))))))))))))))))))))))))))))))));
            return $dir;
        }

        public function createFileView($path)
        {
            $content_file = APPPATH . 'views/' . $path . '.php';
            $default_content = "";
            // Periksa apakah file sudah ada
            if (!file_exists($content_file)) {
                // Buat file baru jika belum ada
                if (!write_file($content_file, $default_content)) {
                    // Jika gagal membuat file, kembalikan false
                    return false;
                }
            }

            // Kembalikan path file content
            return $content_file;

        }

        function generateUUID()
        {
            $data = $data ?? random_bytes(16);
            $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // versi 4
            $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // varian 1

            return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
        }

        function guidv4($data = null)
        {
            // Generate 16 bytes (128 bits) of random data or use the data passed into the function.
            $data = $data ?? random_bytes(16);
            assert(strlen($data) == 16);

            

            // Set version to 0100
            $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
            // Set bits 6-7 to 10
            $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

            // Output the 36 character UUID.
            return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
        }
        public function cors()
        {
            $name = $this->security->get_csrf_token_name();
            $value = $this->security->get_csrf_hash();
            echo  "<input type='hidden' name='$name' value='$value'/>";
        }

        public function verify_token($token)
        {
            // cek apakah token ada di database atau tidak
            $this->db->where('token', $token);
            $query = $this->db->get('token');
            if ($query->num_rows() > 0) {
                // cek apakah token sudah expired atau belum
                $expired_at = $query->row()->expired_at;
                if (strtotime($expired_at) > time()) {
                    return true;
                } else {
                    // hapus token yang sudah expired
                    $this->db->where('token', $token);
                    $this->db->delete('token');
                    return false;
                }
            } else {
                return false;
            }
        }

        public function req_get_data($data)
        {



            error_reporting(0);
            $curl = curl_init();
           

            curl_setopt_array($curl, array(
            CURLOPT_URL => getenv('URL_API') .'/'. $data['url'],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_POSTFIELDS => $data['filter'],
            CURLOPT_HTTPHEADER => array(
                'Authorization: ' . $_SESSION['token'],
                'Content-Type: application/json',
            ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);
            return $response;

        }

        public function req_post_data($data)
        {

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL =>  getenv('URL_API') . '/' . $data['url'],
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $data['data'],
                CURLOPT_HTTPHEADER => array(
                    'Authorization: ' . $_SESSION['token'],
                ),
            ));

            $response = curl_exec($curl);


            curl_close($curl);
            return $response;
        }

        public function req_put_data($data)
        {
            $curl = curl_init();
            $postfields = http_build_query($data['data']);



            curl_setopt_array($curl, array(
                CURLOPT_URL =>  getenv('URL_API') . '/' . $data['url'], 
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'PUT',
                CURLOPT_POSTFIELDS => $postfields,
                CURLOPT_HTTPHEADER => array(
                'Authorization: ' . $_SESSION['token'],
                    'Content-Type: application/x-www-form-urlencoded',
                ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);
            return $response;
        }

        public function req_delete_data($data)
        {
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL =>  getenv('URL_API') . '/' . $data['url'],
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'DELETE',
                CURLOPT_HTTPHEADER => array(
                    'Authorization: ' . $_SESSION['token'],
                ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);
            return $response;
        }
}


