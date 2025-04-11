<form action="<?= base_url('app/user/update/' . $data->id . '') ?>" method="POST">
    <?= $this->req->flash() ?>
    <label for="username"><b>Username</b></label>
    <input type="text" value="<?= $data->username?>" id="username" placeholder="Masukan Data" class="input" name="username" required>

    <label for="password"><b>Password (Abaikan jika tidak akan dirubah)</b></label>
    <input type="password" value="<?= $data->password?>" id="password" placeholder="Masukan Data" class="input" name="password">

    <label for="nama_user"><b>Nama User</b></label>
    <input type="text" value="<?= $data->nama_user?>" id="nama_user" placeholder="Masukan Data" class="input" name="nama_user" required>

    <label for="keterangan"><b>keterangan</b></label>
    <input type="text" value="<?= $data->keterangan?>" id="keterangan" placeholder="Masukan Data" class="input" name="keterangan">

    <label for="status"><b>Apakah Aktif</b></label>
    <select name="status" class="input" value="<?= $data->status?>" id="status">
        <option selected value="1">Ya Aktif</option>
        <option value="0">Tidak</option>
    </select>

    <button type="submit" class="btn">Tambah Data</button>

</form>