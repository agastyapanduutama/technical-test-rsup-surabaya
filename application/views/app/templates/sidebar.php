<div class="sidebar">
    <a class="<?php if($this->uri->segment(2) == "dashboard") echo "active"?>" href="<?= base_url('app/dashboard') ?>">Dashboard</a>
    <!-- <a class="<?php if($this->uri->segment(2) == "menu") echo "active"?>" href="<?= base_url('app/menu') ?>">Menu</a> -->
    <a class="<?php if($this->uri->segment(2) == "role") echo "active"?>" href="<?= base_url('app/role') ?>">Role</a>
    <a class="<?php if($this->uri->segment(2) == "akses") echo "active"?>" href="<?= base_url('app/akses') ?>">Role Akses</a>
    <a class="<?php if($this->uri->segment(2) == "user") echo "active"?>" href="<?= base_url('app/user') ?>">Pengguna</a>
    <a class="<?php if($this->uri->segment(2) == "pelaporan") echo "active"?>" href="<?= base_url('app/pelaporan') ?>">Data Pelaporan K3</a>
    <a class="<?php if($this->uri->segment(2) == "logout") echo "active"?>" href="<?= base_url('app/logout') ?>">Keluar</a>
</div>