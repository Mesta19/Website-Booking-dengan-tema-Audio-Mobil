<?= $this->extend('template/layout') // Menggunakan layout utama Anda ?>

<?= $this->section('title') ?>
    <?= esc($title ?? 'Booking Berhasil') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container">
    <div class="booking-success-container">
        <div class="card success-card">
            <div class="card-header success-header text-center">
                <i class="fas fa-check-circle fa-3x mb-3 text-success-icon"></i>
                <h2><?= esc($title ?? 'Booking Berhasil Dibuat!') ?></h2>
            </div>
            <div class="card-body">
                <p class="lead text-center mb-4">Terima kasih! Booking Anda telah kami terima dan sedang diproses.</p>

                <?php if (!empty($booking)): ?>
                    <div class="booking-details">
                        <h4 class="details-title">Detail Booking Anda:</h4>
                        <div class="detail-grid"> <?php // Kelas baru untuk grid ?>
                            <div class="detail-label">ID Booking:</div>
                            <div class="detail-value">#<?= esc($booking['id_booking']) ?></div>

                            <?php
                            $namaPemesan = esc($booking['id_pelanggan']); 
                            if (session()->get('logged_in_pelanggan') && session()->get('id_pelanggan') == $booking['id_pelanggan']) {
                                $namaPemesan = esc(session()->get('nama_pelanggan'));
                            } elseif (session()->get('logged_in_admin')) {
                                // Jika admin, dan Anda punya cara untuk mengambil nama pelanggan berdasarkan ID:
                                // $pelangganModel = new \App\Models\PelangganModel();
                                // $pelangganData = $pelangganModel->find($booking['id_pelanggan']);
                                // $namaPemesan = $pelangganData ? esc($pelangganData['nama_pelanggan']) : 'Pelanggan: ' . esc($booking['id_pelanggan']);
                                $namaPemesan = 'Booking oleh Admin untuk Pelanggan ID: ' . esc($booking['id_pelanggan']);
                            }
                            ?>
                            <div class="detail-label">Nama Pemesan:</div>
                            <div class="detail-value"><?= $namaPemesan ?></div>

                            <div class="detail-label">Tanggal Booking:</div>
                            <div class="detail-value">
                                <?php if (function_exists('format_indo_datetime')): ?>
                                    <?= esc(format_indo_datetime($booking['tanggal_booking'], 'eeee, d MMMM yyyy')) // Hanya tanggal, pola diperbaiki ?>
                                <?php else: ?>
                                    <?= esc(date('d M Y', strtotime($booking['tanggal_booking']))) ?>
                                <?php endif; ?>
                            </div>

                            <div class="detail-label">Total Harga:</div>
                            <div class="detail-value">Rp <?= number_format(esc($booking['total_harga']), 0, ',', '.') ?></div>
                        </div>
                    </div>

                    <?php if (!empty($detail_layanan) && is_array($detail_layanan)): ?>
                        <div class="layanan-summary mt-4">
                            <h5 class="summary-title">Layanan yang Dibooking:</h5>
                            <ul class="list-group list-group-flush">
                                <?php foreach ($detail_layanan as $layanan): ?>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <?= esc($layanan['nama_layanan']) ?>
                                        <span>Rp <?= number_format(esc($layanan['harga']), 0, ',', '.') ?></span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <p class="text-center text-danger">Detail booking tidak dapat ditampilkan saat ini.</p>
                <?php endif; ?>

                <hr class="my-4">

                <p class="text-center instructions">
                    Silakan simpan informasi booking ini. Anda akan dihubungi oleh tim kami untuk konfirmasi lebih lanjut mengenai jadwal servis Anda.
                    Untuk pertanyaan, hubungi kami di <a href="mailto:globalservice4545@gmail.com">globalservice4545@gmail.com</a> atau Whats'App <a href="https://wa.me/6287881620835">+62 878 8162 0835</a>
                </p>

                <div class="text-center mt-4">
                    <a href="<?= base_url('booking/saya') ?>" class="btn btn-primary-action"><i class="fas fa-list"></i> Booking Saya</a>
                    <?php if(session()->get('logged_in_pelanggan')): ?>
                        <?php // Tambahkan link ke riwayat booking jika rutenya sudah ada ?>
                        <?php // <a href=" // echo url_to('pelanggan_booking_history') " class="btn btn-outline-secondary-action ms-2"><i class="fas fa-history"></i> Riwayat Booking Saya</a> ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('pageStyles') ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .booking-success-container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 1rem;
        }
        .success-card {
            background-color: #1e1e1e; /* Warna latar kartu */
            border: 1px solid #2c2c2c; /* Border kartu */
            border-radius: 10px; /* Sudut lebih bulat */
            box-shadow: 0 5px 25px rgba(0,0,0,0.3); /* Shadow lebih dalam */
        }
        .success-header {
            background-color: #252525; /* Warna header kartu */
            border-bottom: 1px solid #383838;
            padding: 2rem 1.5rem;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }
        .success-header h2 {
            color: #0a84ff;
            margin-top: 0.5rem;
            margin-bottom: 0;
            font-size: 2rem;
            font-weight: 600;
        }
        .text-success-icon {
            color: #28a745 !important; /* Warna ikon ceklis hijau */
        }
        .card-body {
            padding: 2rem 2.5rem; /* Padding lebih besar di body */
            color: #c0c0c0; /* Warna teks umum */
        }
        .lead {
            font-size: 1.15rem;
            font-weight: 400;
            color: #e0e0e0;
        }
        .booking-details {
            background-color: #282828; /* Latar belakang detail booking */
            padding: 1.5rem;
            border-radius: 6px;
            margin-top: 1.5rem;
            border: 1px solid #3a3a3a;
        }
        .details-title, .summary-title {
            color: #0a84ff;
            margin-bottom: 1rem;
            font-size: 1.25rem;
            font-weight: 600;
            border-bottom: 1px solid #383838;
            padding-bottom: 0.5rem;
        }
        .booking-details p {
            margin-bottom: 0.6rem;
            font-size: 0.95rem;
        }
        .booking-details p strong {
            color: #e0e0e0; /* Warna teks bold */
            min-width: 130px; /* Lebar minimum untuk label */
            display: inline-block;
        }
        .layanan-summary .list-group-item {
            background-color: transparent; /* Transparan agar warna latar .layanan-summary terlihat */
            border-color: #383838; /* Warna border item list */
            color: #c0c0c0;
            padding: 0.75rem 0; /* Padding atas bawah item */
            font-size: 0.95rem;
        }
        .layanan-summary .list-group-item span {
            color: #e0e0e0;
            font-weight: 500;
        }
        .instructions {
            font-size: 0.9rem;
            line-height: 1.6;
            color: #adb5bd;
        }
        .instructions a {
            color: #0a84ff;
            text-decoration: none;
        }
        .instructions a:hover {
            text-decoration: underline;
        }
        hr.my-4 {
            margin-top: 2rem !important;
            margin-bottom: 2rem !important;
            border-top: 1px solid #383838;
        }
        .btn-primary-action { 
            /* Style ini seharusnya sudah ada di style.css utama Anda */
            background-color: #0a84ff; color: white !important; padding: 0.75rem 1.5rem; 
            text-decoration: none; border-radius: 5px; font-weight: 600; border: none;
            display: inline-flex; align-items: center;
        }
        .btn-primary-action:hover { background-color: #006ecc; }
        .btn-outline-secondary-action {
            /* Contoh style untuk tombol outline */
            color: #adb5bd !important; border: 1px solid #6c757d; padding: 0.75rem 1.5rem;
            text-decoration: none; border-radius: 5px; font-weight: 600;
            display: inline-flex; align-items: center;
        }
        .btn-outline-secondary-action:hover { background-color: #383838; color: #e0e0e0 !important; }
        .btn i { margin-right: 0.5rem; }
        .ms-2 { margin-left: 0.5rem !important; } /* Jarak antar tombol */
        .text-center { text-align: center !important; }
        .mb-3 { margin-bottom: 1rem !important; }
        .mb-4 { margin-bottom: 1.5rem !important; }
        .mt-4 { margin-top: 1.5rem !important; }
        .fa-3x { font-size: 3em; }
        .text-danger { color: #dc3545 !important; }
    </style>
<?= $this->endSection() ?>