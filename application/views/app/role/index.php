<?= $this->req->flash() ?>
<a href="<?= base_url('app/role/add'); ?>">Tambah Data</a>
<table class="table">
    <thead>
        <tr>
            <th>Nama Role</th>
            <th>Keterangan</th>
            <th>Hak Akses (1 baca, 2 kelola, 3 Full)</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>

    </tbody>
</table>

<script>
    // Tampilkan data role
    fetch('<?= base_url('app/role/data'); ?>')
        .then(response => response.json())
        .then(data => {
            let html = '';
            data.forEach(value => {
                html += '<tr>';
                html += '<td>' + value.nama_role + '</td>';
                html += '<td>' + value.keterangan + '</td>';
                html += '<td>' + value.hak_akses + '</td>';
                html += '<td>';
                html += '<a href="<?= base_url('app/role/edit/'); ?>' + value.id + '">Edit</a>|';
                html += '<a href="#" class="delete" data-id="' + value.id + '">Delete</a>';
                html += '</td>';
                html += '</tr>';
            });
            document.querySelector('tbody').innerHTML = html;
        });

    // Hapus data role
    document.addEventListener('click', function(event) {

        // confirm before delete
        if (event.target.classList.contains('delete')) {
            if (!confirm('Apakah Anda yakin ingin menghapus data ini?')) {
                event.preventDefault();
                return false;
            }
        }
        // jika tombol delete diklik
        if (event.target.classList.contains('delete')) {
            let id = event.target.getAttribute('data-id');
            fetch('<?= base_url('app/role/delete/'); ?>' + id, {
                    method: 'DELETE'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        alert(data.message);
                        console.log(data);
                    } else {
                        alert(data.message);
                        console.log(data);
                    }
                    // Tampilkan data role lagi
                    fetch('<?= base_url('app/role/data'); ?>')
                        .then(response => response.json())
                        .then(data => {
                            let html = '';
                            data.forEach(value => {
                                html += '<tr>';
                                html += '<td>' + value.nama_role + '</td>';
                                html += '<td>' + value.keterangan + '</td>';
                                html += '<td>' + value.hak_akses + '</td>';
                                html += '<td>';
                                html += '<a href="<?= base_url('app/role/edit/'); ?>' + value.id + '">Edit</a>|';
                                html += '<a href="#" class="delete" data-id="' + value.id + '">Delete</a>';
                                html += '</td>';
                                html += '</tr>';
                            });
                            document.querySelector('tbody').innerHTML = html;
                        });
                })
                .catch(error => {
                    alert('Gagal menghapus data!');
                    console.error(error);
                    console.error(data);
                    console.error(response);
                });
        }


    });
</script>