<form action="<?= base_url('app/akses/insert') ?>" method="POST">
    <?= $this->req->flash() ?>
 
    <label for="id_role"><b>Role</b></label>
    <select name="id_role" class="input" id="id_role">
        <option value="">Pilih Role</option>
        <?php foreach ($role as $row) : ?>
            <option value="<?= $row->id ?>"><?= $row->nama_role ?></option>
        <?php endforeach; ?>
    </select>
 
    <label for="id_user"><b>Pengguna</b></label>
    <select name="id_user" class="input" id="id_user">
        <option value="">Pilih Pengguna</option>
        <?php foreach ($user as $row) : ?>
            <option value="<?= $row->id ?>"><?= $row->nama_user ?></option>
        <?php endforeach; ?>
    </select>
    

    <button type="submit" class="btn">Tambah Data</button>

</form>