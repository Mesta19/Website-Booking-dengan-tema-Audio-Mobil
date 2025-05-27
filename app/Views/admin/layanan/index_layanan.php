<?= $this->extend('template/layout') // Sesuaikan dengan path layout admin Anda ?>

<?= $this->section('title') ?>
    <?= esc($title ?? 'Manajemen Layanan') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container">
    <div class="admin-content-container">
        <div class="d-flex justify-content-between align-items-center mb-4"> <?php // Wrapper untuk judul dan tombol tambah ?>
            <h2><?= esc($title ?? 'Manajemen Layanan') ?></h2>
            <a href="<?= url_to('admin_layanan_tambah') ?>" class="btn btn-primary-action">
                <i class="fas fa-plus"></i> Tambah Layanan
            </a>
        </div>

        <?php if(session()->getFlashdata('success')): ?>
            <div class="alert alert-success" role="alert"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>
        <?php if(session()->getFlashdata('error')): ?>
            <div class="alert alert-danger" role="alert"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>

        <div class="table-responsive">
            <table class="table table-bordered table-hover admin-table">
                <thead class="thead-dark">
                    <tr>
                        <th style="width: 10%;">ID</th>
                        <th>Nama Layanan</th>
                        <th style="width: 15%;">Harga</th>
                        <th style="width: 10%;">Status</th>
                        <th style="width: 20%;">Dibuat Pada</th>
                        <th style="width: 20%; text-align: center;">Aksi</th> <?php // Kolom Aksi lebih lebar & teks tengah ?>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($layanan) && is_array($layanan)): ?>
                        <?php foreach ($layanan as $item): ?>
                            <tr class="<?= ($item['is_delete_layanan'] == '1') ? 'table-row-deleted' : '' ?>">
                                <td><?= esc($item['id_layanan']) ?></td>
                                <td><?= esc($item['nama_layanan']) ?></td>
                                <td>Rp <?= number_format(esc($item['harga']), 0, ',', '.') ?></td>
                                <td class="text-center"> <?php // Status teks tengah ?>
                                    <?= ($item['is_delete_layanan'] == '0') ? '<span class="badge badge-success">Aktif</span>' : '<span class="badge badge-danger">Tidak Aktif</span>' ?>
                                </td>
                                <td>
                                    <?php if (function_exists('format_indo_datetime')): ?>
                                        <?= esc(format_indo_datetime($item['created_at'])) ?>
                                    <?php else: ?>
                                        <?= esc(date('d M Y, H:i', strtotime($item['created_at']))) ?>
                                    <?php endif; ?>
                                </td>
                                <td class="actions-cell"> <?php // Kelas baru untuk styling sel aksi ?>
                                    <?php if ($item['is_delete_layanan'] == '0'): ?>
                                        <a href="<?= url_to('admin_layanan_edit', $item['id_layanan']) ?>" class="btn btn-sm btn-edit" title="Edit">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <?php // Tombol hapus sekarang menggunakan SweetAlert2 ?>
                                        <button type="button" class="btn btn-sm btn-delete" title="Hapus" 
                                                data-id="<?= esc($item['id_layanan']) ?>" 
                                                data-nama="<?= esc($item['nama_layanan']) ?>"
                                                onclick="confirmDelete(this)">
                                            <i class="fas fa-trash-alt"></i> Hapus
                                        </button>
                                        <?php // Form untuk submit penghapusan (tersembunyi) ?>
                                        <form id="delete-form-<?= esc($item['id_layanan']) ?>" action="<?= url_to('admin_layanan_hapus_proses', $item['id_layanan']) ?>" method="post" style="display: none;">
                                            <?= csrf_field() ?>
                                        </form>
                                    <?php else: ?>
                                        <span class="text-muted"><em>Telah dihapus</em></span>
                                        <?php // Opsi untuk mengaktifkan kembali bisa ditambahkan di sini ?>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center py-4">Belum ada data layanan.</td> <?php // Padding vertikal jika kosong ?>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('pageStyles') // Untuk CSS tambahan jika perlu ?>
<?= $this->endSection() ?>

<?= $this->section('pageScripts') // Untuk JavaScript tambahan jika perlu ?>
    <?php // Pastikan SweetAlert2 sudah di-include di layout utama Anda ?>
    <script>
        function confirmDelete(button) {
            const idLayanan = button.getAttribute('data-id');
            const namaLayanan = button.getAttribute('data-nama');
            const form = document.getElementById('delete-form-' + idLayanan);

            Swal.fire({
                title: 'Konfirmasi Hapus',
                html: `Anda yakin ingin menghapus layanan "<strong>${namaLayanan}</strong>"?<br><small style="color: #f8bb86;">(Status layanan akan diubah menjadi Tidak Aktif)</small>`, // Warna teks catatan
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33', // Merah standar SweetAlert
                cancelButtonColor: '#3085d6', // Biru standar SweetAlert
                confirmButtonText: '<i class="fas fa-trash-alt"></i> Ya, Hapus!',
                cancelButtonText: '<i class="fas fa-times"></i> Tidak, Batal',
                reverseButtons: true, // Tombol konfirmasi di kanan
                customClass: {
                    popup: 'swal2-dark-popup',
                    title: 'swal2-dark-title',
                    htmlContainer: 'swal2-dark-html-container',
                    confirmButton: 'btn btn-danger swal-confirm-button', // Kelas kustom untuk tombol
                    cancelButton: 'btn btn-primary swal-cancel-button'   // Kelas kustom untuk tombol
                },
                buttonsStyling: false // Nonaktifkan styling default SweetAlert agar kelas kustom bekerja
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }
    </script>
    <?php // Tambahkan style untuk tema gelap SweetAlert2 dan tombol kustom jika belum ada di CSS utama ?>
    <style>
        .swal2-dark-popup { background-color: #282c34 !important; color: #abb2bf !important; border-radius: 8px !important;}
        .swal2-dark-title { color: #0a84ff !important; }
        .swal2-dark-html-container { color: #abb2bf !important; }
        /* Styling untuk tombol kustom SweetAlert */
        .swal-confirm-button, .swal-cancel-button {
            margin: 0 5px !important; /* Jarak antar tombol di dialog */
            padding: 0.6em 1.2em !important;
            font-weight: 600 !important;
            border-radius: 5px !important;
        }
        .swal-confirm-button i, .swal-cancel-button i {
            margin-right: 5px;
        }
    </style>
<?= $this->endSection() ?>