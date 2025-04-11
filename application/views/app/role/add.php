<form action="<?= base_url('app/role/insert') ?>" method="POST">
    <?= $this->req->flash() ?>
    <label for="nama_role"><b>Nama Role</b></label>
    <input type="text" id="nama_role" placeholder="Masuk data" class="input" name="nama_role" required>

    <label for="keterangan"><b>keterangan</b></label>
    <input type="text" id="keterangan" placeholder="Masuk data" class="input" name="keterangan">

    <label for="hak_akses"><b>Hak Akses</b></label>
    <select name="hak_akses" class="input" id="hak_akses">
        <option value="1">Baca</option>
        <option value="2">Kelola</option>
        <option value="3">Full</option>
    </select>

    <button type="submit" class="btn">Tambah Data</button>

</form>