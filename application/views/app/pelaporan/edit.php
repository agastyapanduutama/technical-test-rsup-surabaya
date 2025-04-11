<form action="<?= base_url('app/pelaporan/update/' . $data->id . '') ?>" enctype="multipart/form-data" method="POST">
    <?= $this->req->flash() ?>
    <label for="nama_insiden"><b>Nama Insiden</b></label>
    <input type="text" value="<?= $data->nama_insiden?>" id="nama_insiden" placeholder="Masuk data" class="input" name="nama_insiden" required>

    <label for="lokasi"><b>Lokasi</b></label>
    <input type="text" value="<?= $data->lokasi?>" id="lokasi" placeholder="Masuk data" class="input" name="lokasi" required>

    <label for="waktu_insiden"><b>Waktu Insiden</b></label>
    <input type="datetime-local"  value="<?= $data->waktu_insiden?>" id="waktu_insiden" placeholder="Masuk data" class="input" name="waktu_insiden">

    <label for="keterangan"><b>Keterangan (Opsional)</b></label>
    <input type="text" value="<?= $data->keterangan?>" id="keterangan" placeholder="Masuk data" class="input" name="keterangan">

    <label for="berkas"><b>Berkas Pendukung (Opsional)</b></label>
    <input type="file" value="<?= $data->berkas?>" id="berkas" placeholder="Masuk data" class="input" name="berkas">
    <?php if(isset($berkas->berkas)):?>
        <a href="<?= base_url('assets/uploads/berkas_insiden/'.$berkas->berkas) ?>" target="_blank"><?= $berkas->nama_berkas?></a>
    <?php endif?>
    <br>
    <br>


    <label for="status_insiden"><b>Status Insiden</b></label>
    <select name="status_insiden" class="input" id="status_insiden">
        <option <?php if($data->status_insiden == 0) echo "selected" ?> value="0">Baru</option>
        <option <?php if($data->status_insiden == 1) echo "selected" ?> value="1">Diproses/Ditindak</option>
        <option <?php if($data->status_insiden == 2) echo "selected" ?> value="2">Selesai</option>
    </select>

    <label for="pelapor"><b>pelapor</b></label>
    <input type="text" readonly value="<?= $data->nama_user ?>" id="pelapor" placeholder="Masuk data" class="input">
    <input type="hidden" value="<?= $data->id_user ?>" id="id_user" placeholder="Masuk data" class="input" name="id_user">
    <input type="hidden" value="<?= $berkas->id ?>" id="iddataberkas" placeholder="Masuk data" class="input" name="iddataberkas">


    <button type="submit" class="btn">Tambah Data</button>

</form>