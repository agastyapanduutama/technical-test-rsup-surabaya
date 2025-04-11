<form action="<?= base_url('app/user/insert') ?>" method="POST">
    <?= $this->req->flash() ?>
    <label for="username"><b>Username</b></label>
    <input type="text" id="username" placeholder="Masukan Data" class="input" name="username" required>

    <label for="password"><b>Password</b></label>
    <input type="password" id="password" placeholder="Masukan Data" class="input" name="password" required>

    <label for="nama_user"><b>Nama User</b></label>
    <input type="text" id="nama_user" placeholder="Masukan Data" class="input" name="nama_user" required>

    <label for="keterangan"><b>keterangan</b></label>
    <input type="text" id="keterangan" placeholder="Masukan Data" class="input" name="keterangan">

    <label for="status"><b>Apakah Aktif</b></label>
    <select name="status" class="input" id="status">
        <option selected value="1">Ya Aktif</option>
        <option value="0">Tidak</option>
    </select>

    <button type="submit" class="btn">Tambah Data</button>

</form>