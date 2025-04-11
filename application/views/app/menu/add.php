<form action="<?= base_url('app/menu/insert') ?>" method="POST">
    <?= $this->req->flash() ?>
    <label for="nama_menu"><b>Nama Menu</b></label>
    <input type="text" id="nama_menu" placeholder="Masuk data" class="input" name="nama_menu" required>

    <label for="link"><b>Link</b></label>
    <input type="text" id="link" placeholder="Masuk data" class="input" name="link" required>

    <label for="keterangan"><b>keterangan</b></label>
    <input type="text" id="keterangan" placeholder="Masuk data" class="input" name="keterangan">

    <label for="status"><b>Apakah Aktif</b></label>
    <select name="status" class="input" id="status">
        <option selected value="1">Ya Aktif</option>
        <option value="0">Tidak</option>
    </select>

    <button type="submit" class="btn">Tambah Data</button>

</form>