<!DOCTYPE html>
<html>

<head>
    <title><?= $title ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="<?= base_url('assets/css/login.css') ?>">
    <script src="https://www.google.com/recaptcha/api.js"></script>
    <meta http-equiv="Cache-Control" content="no-store">

</head>

<body>

    <h2>Masuk Aplikasi</h2>

    <form id="login-form" action="<?= base_url('login/aksi') ?>" method="post">

        <div class="container">
            <?= $this->req->flash()?>
            <?= $this->req->cors() ?>

            <label for="username"><b>Username</b></label>
            <input type="text" id="username" placeholder="Masukan Username anda" class="input" name="username" required>

            <label for="password"><b>Password</b></label>
            <input type="password" id="password" placeholder="Masukan Password anda" class="input" name="password" required>



            <div class="g-recaptcha" data-sitekey="6LflqhArAAAAABL3kfheIa5gH6D22h-Jc4ADMi4U"></div>

            <label>
                <input type="checkbox" checked="checked" name="remember"> Ingat Saya
            </label>
            <button>Masuk Aplikasi</button>

        </div>


    </form>

</body>

</html>