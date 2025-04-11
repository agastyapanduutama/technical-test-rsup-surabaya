<!DOCTYPE html>
<html>
<title><?= $title?></title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/5/w3.css">
<link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">

<body>

    <?php include 'sidebar.php' ?>

    <!-- Page Content -->
    <div class="content">

        <h2><?= $title ?></h2>
        <div class="container">
            <?php $this->load->view($content); ?>
        </div>

    </div>

</body>

</html>