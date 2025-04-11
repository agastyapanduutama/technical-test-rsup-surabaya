<form action="<?= base_url('app/role/update/' . $data->id . '') ?>" method="POST">
    <?= $this->req->flash() ?>
    <label for="nama_role"><b>Nama Role</b></label>
    <input type="text" value="<?= $data->nama_role?>" id="nama_role" placeholder="Masuk data" class="input" name="nama_role" required>

    <label for="keterangan"><b>keterangan</b></label>
    <input type="text" value="<?= $data->keterangan?>" id="keterangan" placeholder="Masuk data" class="input" name="keterangan">

    <label for="hak_akses"><b>Hak Akses</b></label>
    <select name="hak_akses" class="input" id="hak_akses">
        <option <?php if($data->hak_akses == 1) echo "selected"?> value="1">Baca</option>
        <option <?php if($data->hak_akses == 2) echo "selected"?> value="2">Kelola</option>
        <option <?php if($data->hak_akses == 3) echo "selected"?> value="3">Full</option>
    </select>

    <button type="submit" class="btn">Tambah Data</button>

</form>