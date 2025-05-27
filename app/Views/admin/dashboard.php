<?= $this->extend('template/layout_admin') ?>

<?= $this->section('title') ?>
    Panel Admin | Dashboard
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container admin-content-container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Kelola Daftar Booking</h2>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success" role="alert">
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-error" role="alert">
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($bookings)): ?>
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Pelanggan</th>
                        <th>Tanggal</th>
                        <th>Layanan</th>
                        <th>Harga</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($bookings as $booking): ?>
                        <tr>
                            <td><?= esc($booking['id_booking']) ?></td>
                            <td>
                                <?php if ($booking['pelanggan']): ?>
                                    <strong><?= esc($booking['pelanggan']['nama_pelanggan']) ?></strong>
                                    <small class="text-muted">(ID: <?= esc($booking['pelanggan']['id_pelanggan']) ?>)</small>
                                    <?php if (!empty($booking['pelanggan']['no_hp'])): ?>
                                        <br>
                                        <small class="text-muted">Tel: <?= esc($booking['pelanggan']['no_hp']) ?></small>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="text-danger">Data Pelanggan Tidak Ditemukan</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center"><?= esc(date('d-m-Y', strtotime($booking['tanggal_booking']))) ?></td>
                            <td>
                                <?php if (!empty($booking['detail_layanan'])): ?>
                                    <ul>
                                        <?php foreach ($booking['detail_layanan'] as $layanan): ?>
                                            <li><?= esc($layanan['nama_layanan']) ?> <small>(Rp <?= number_format(esc($layanan['harga']), 0, ',', '.') ?>)</small></li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php else: ?>
                                    <span class="text-muted">Tidak ada layanan</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-right">Rp <?= number_format(esc($booking['total_harga']), 0, ',', '.') ?></td>
                            <td class="actions-cell">
        <?php if ($booking['pelanggan'] && !empty($booking['pelanggan']['no_hp'])): ?>
            <?php
                $phoneNumber = esc($booking['pelanggan']['no_hp']);
                $phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);
                if (substr($phoneNumber, 0, 1) === '0') {
                    $phoneNumber = '62' . substr($phoneNumber, 1);
                } elseif (substr($phoneNumber, 0, 2) !== '62' && substr($phoneNumber, 0, 1) === '8') {
                    $phoneNumber = '62' . $phoneNumber;
                }
                $whatsappLink = 'https://wa.me/' . $phoneNumber;
            ?>
            <a href="<?= $whatsappLink ?>" target="_blank" class="btn btn-success btn-sm"><i class="fab fa-whatsapp mr-1"></i> WhatsApp</a>
        <?php else: ?>
            <span class="text-muted">No. HP Tidak Tersedia</span>
        <?php endif; ?>

        <form action="<?= url_to('admin_booking_hapus_proses', $booking['id_booking']) ?>" method="post" class="d-inline mt-2 form-delete">
            <?= csrf_field() ?>
            <button type="submit" class="btn btn-delete btn-sm"><i class="fas fa-trash mr-1"></i> Hapus</button>
        </form>
    </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="alert alert-info">
            Belum ada data booking.
        </div>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>

<?= $this->section('pageStyles') ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        /* Style tambahan khusus untuk halaman ini jika diperlukan */
    </style>
<?= $this->endSection() ?>

<?= $this->section('pageScripts') ?>
<script>
    const deleteForms = document.querySelectorAll('.form-delete');
    deleteForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault(); // Mencegah submit form secara langsung
            Swal.fire({
                title: 'Konfirmasi Hapus',
                text: "Apakah Anda yakin ingin menghapus booking ini?",
                icon: 'warning', // Ikon bisa dipertahankan
                showCancelButton: true,
                confirmButtonColor: '#dc3545', // Merah untuk tombol konfirmasi (Hapus)
                cancelButtonColor: '#6e7881',  // Abu-abu untuk tombol batal
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                customClass: {
                    popup: 'swal2-dark-popup',
                    title: 'swal2-dark-title',
                    htmlContainer: 'swal2-dark-html-container', // atau 'text-container' tergantung versi swal
                    icon: 'swal2-dark-icon',
                    confirmButton: 'swal2-dark-confirm-button btn btn-danger', // Tambahkan class Bootstrap jika diinginkan
                    cancelButton: 'swal2-dark-cancel-button btn btn-secondary' // Tambahkan class Bootstrap jika diinginkan
                },
                buttonsStyling: false // Penting agar customClass bisa menimpa style default
            }).then((result) => {
                if (result.isConfirmed) {
                    // Jika dikonfirmasi, lanjutkan submit form asli
                    // Perlu cara untuk men-submit form tanpa memicu event listener ini lagi
                    // Cara 1: Menggunakan flag
                    // form.dataset.confirmed = 'true';
                    // form.submit();

                    // Cara 2: Melepas event listener sementara (lebih rumit jika banyak form)
                    
                    // Cara 3 (Lebih Sederhana): Buat form baru secara dinamis atau submit via AJAX
                    // Untuk kasus ini, cara paling sederhana adalah membiarkan submit() standar,
                    // karena e.preventDefault() hanya mencegah submit awal.
                    // Jika tidak ada logika kompleks lain, form.submit() seharusnya bekerja.
                    form.submit(); 
                }
            })
        });
    });
</script>

<style>
    /* Styling untuk SweetAlert2 Dark Theme */
    .swal2-dark-popup {
        background-color: #282c34 !important; /* Latar belakang popup gelap */
        color: #abb2bf !important; /* Warna teks umum terang */
        border-radius: 8px !important;
        border: 1px solid #4b5461; /* Border tipis (opsional) */
    }

    .swal2-dark-title {
        color: #0a84ff !important; /* Warna judul, bisa disesuaikan agar kontras. Contoh: putih atau biru muda */
        /* Atau, jika ingin warna putih standar untuk title di dark mode: */
        /* color: #e0e0e0 !important; */
    }

    .swal2-dark-html-container { /* Ini menargetkan kontainer teks */
        color: #abb2bf !important; /* Warna teks konten */
    }

    .swal2-icon.swal2-warning.swal2-dark-icon {
        border-color: #f8bb86 !important; /* Warna border ikon warning */
        color: #f8bb86 !important; /* Warna ikon warning */
    }
    .swal2-dark-confirm-button,
    .swal2-dark-cancel-button {
        padding: 0.6em 1.2em !important;
        font-weight: 600 !important;
        border-radius: 5px !important;
        margin: 0 5px !important;
        transition: background-color 0.2s ease, border-color 0.2s ease;
    }
</style>
<?= $this->endSection() ?>