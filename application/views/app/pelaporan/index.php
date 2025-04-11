<?= $this->req->flash() ?>
<a href="<?= base_url('app/pelaporan/add'); ?>">Tambah Data</a>
<table class="table">
    <thead>
        <tr>
            <th>Nama Insiden</th>
            <th>Lokasi</th>
            <th>Waktu Insiden</th>
            <th>Keterangan</th>
            <th>Status Insiden ( 0 = belum di proses 1 = ditindak 2 = selesai )</th>
            <th>Waktu Dibuat</th>
            <th>Terakhir Dirubah</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>

    </tbody>
</table>

<script>
    // Tampilkan data insiden
    fetch('<?= base_url('app/pelaporan/data'); ?>')
        .then(response => response.json())
        .then(data => {
            let html = '';
            data.forEach(value => {
                html += '<tr>';
                html += '<td>' + value.nama_insiden + '</td>';
                html += '<td>' + value.lokasi + '</td>';
                html += '<td>' + value.waktu_insiden + '</td>';
                html += '<td>' + value.keterangan + '</td>';
                html += '<td>' + value.status_insiden + '</td>';
                html += '<td>' + value.created_at + '</td>';
                html += '<td>' + value.updated_at + '</td>';
                html += '<td>';
                html += '<a href="<?= base_url('app/pelaporan/detail/'); ?>' + value.id + '">Detail</a>|';
                html += '<a href="<?= base_url('app/pelaporan/edit/'); ?>' + value.id + '">Edit</a>|';
                html += '<a href="#" class="delete" data-id="' + value.id + '">Delete</a>';
                html += '</td>';
                html += '</tr>';
            });
            document.querySelector('tbody').innerHTML = html;
        });

    // Hapus data insiden
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
            fetch('<?= base_url('app/pelaporan/delete/'); ?>' + id, {
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
                    // Tampilkan data pelaporan lagi
                    fetch('<?= base_url('app/pelaporan/data'); ?>')
                        .then(response => response.json())
                        .then(data => {
                            let html = '';
                            data.forEach(value => {
                                html += '<tr>';
                                html += '<td>' + value.nama_insiden + '</td>';
                                html += '<td>' + value.lokasi + '</td>';
                                html += '<td>' + value.waktu_insiden + '</td>';
                                html += '<td>' + value.keterangan + '</td>';
                                html += '<td>' + value.status_insiden + '</td>';
                                html += '<td>' + value.created_at + '</td>';
                                html += '<td>' + value.updated_at + '</td>';
                                html += '<td>';
                                html += '<a href="<?= base_url('app/pelaporan/detail/'); ?>' + value.id + '">Detail</a>|';
                                html += '<a href="<?= base_url('app/pelaporan/edit/'); ?>' + value.id + '">Edit</a>|';
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