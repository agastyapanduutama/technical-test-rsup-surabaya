<form action="<?= base_url('app/menu/update/' . $data->id . '') ?>" method="POST">
    <?= $this->req->flash() ?>
    <label for="nama_menu"><b>Nama Menu</b></label>
    <input type="text" value="<?= $data->nama_menu ?>" id="nama_menu" placeholder="Masukan data" class="input" name="nama_menu" required>

    <label for="link"><b>Link</b></label>
    <input type="text" value="<?= $data->link ?>" id="link" placeholder="Masukan data" class="input" name="link" required>

    <label for="keterangan"><b>keterangan</b></label>
    <input type="text" value="<?= $data->keterangan ?>" id="keterangan" placeholder="Masukan data" class="input" name="keterangan">

    <label for="status"><b>Apakah Aktif</b></label>
    <select name="status" class="input" id="status">
        <option <?php if ($data->status == 1) echo "selected" ?> value="1">Ya Aktif</option>
        <option <?php if ($data->status == 0) echo "selected" ?> value="0">Tidak</option>
    </select>

    <button type="submit" class="btn">Perbarui Data</button>

</form>