<form action="<?= base_url('app/pelaporan/update/' . $data->id . '') ?>" enctype="multipart/form-data" method="POST">
    <?= $this->req->flash() ?>
    <label for="nama_insiden"><b>Nama Insiden</b></label>
    <p><?= $data->nama_insiden ?></p>


    <label for="lokasi"><b>Lokasi</b></label>
    <p><?= $data->lokasi ?></p>

    <label for="waktu_insiden"><b>Waktu Insiden</b></label>

    <p><?= $data->waktu_insiden ?></p>
    <label for="keterangan"><b>Keterangan (Opsional)</b></label>
    <p><?= $data->keterangan ?></p>

    <label for="berkas"><b>Berkas Pendukung </b></label>
    <?php if (isset($berkas->berkas)): ?>
        <p>
            <a href="<?= base_url('assets/uploads/berkas_insiden/' . $berkas->berkas) ?>" target="_blank"><?= $berkas->nama_berkas ?></a>

        </p>
    <?php endif ?>


    <label for="status_insiden"><b>Status Insiden</b></label>
    <p>
        <?php if ($data->status_insiden == 0) echo "Baru"; ?>
        <?php if ($data->status_insiden == 1) echo "Diproses/Ditindak"; ?>
        <?php if ($data->status_insiden == 2) echo "Selesai"; ?>
    </p>

    <label for="pelapor"><b>pelapor</b></label>
    <p><?= $data->nama_user ?></p>


</form>