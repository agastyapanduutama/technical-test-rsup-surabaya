<form action="<?= base_url('app/pelaporan/insert') ?>" enctype="multipart/form-data" method="POST">
    <?= $this->req->flash() ?>
    <label for="nama_insiden"><b>Nama Insiden</b></label>
    <input type="text" id="nama_insiden" placeholder="Masuk data" class="input" name="nama_insiden" required>

    <label for="lokasi"><b>Lokasi</b></label>
    <input type="text" id="lokasi" placeholder="Masuk data" class="input" name="lokasi" required>

    <label for="waktu_insiden"><b>Waktu Insiden</b></label>
    <input type="date" value="<?= date('Y-m-d') ?>" id="waktu_insiden" placeholder="Masuk data" class="input" name="waktu_insiden">

    <label for="keterangan"><b>Keterangan (Opsional)</b></label>
    <input type="text" id="keterangan" placeholder="Masuk data" class="input" name="keterangan">

    <label for="berkas"><b>Berkas Pendukung (Opsional)</b></label>
    <input type="file" id="berkas" placeholder="Masuk data" class="input" name="berkas">


    <label for="status_insiden"><b>Status Insiden</b></label>
    <select name="status_insiden" class="input" id="status_insiden">
        <option selected value="0">Baru</option>
        <option value="1">Diproses/Ditindak</option>
        <option value="2">Selesai</option>
    </select>

    <label for="pelapor"><b>pelapor</b></label>
    <input type="text" readonly value="<?= $_SESSION['nama_user'] ?>" id="pelapor" placeholder="Masuk data" class="input">
    <input type="hidden" value="<?= $_SESSION['id_user'] ?>" id="id_user" placeholder="Masuk data" class="input" name="id_user">


    <button type="submit" class="btn">Tambah Data</button>

</form>