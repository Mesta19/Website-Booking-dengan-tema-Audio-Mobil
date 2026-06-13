<?= $this->extend('template/layout') // Menggunakan layout utama Anda 
?>

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
                        <div class="detail-grid">
                            <div class="detail-label">ID Booking:</div>
                            <div class="detail-value">#<?= esc($booking['id_booking']) ?></div>

                            <?php
                            $namaPemesan = esc($booking['id_pelanggan']);
                            if (session()->get('logged_in_pelanggan') && session()->get('id_pelanggan') == $booking['id_pelanggan']) {
                                $namaPemesan = esc(session()->get('nama_pelanggan'));
                            } elseif (session()->get('logged_in_admin')) {
                                $namaPemesan = 'Booking oleh Admin untuk Pelanggan ID: ' . esc($booking['id_pelanggan']);
                            }
                            ?>
                            <div class="detail-label">Nama Pemesan:</div>
                            <div class="detail-value"><?= $namaPemesan ?></div>

                            <div class="detail-label">Tanggal Booking:</div>
                            <div class="detail-value">
                                <?php if (function_exists('format_indo_datetime')): ?>
                                    <?= esc(format_indo_datetime($booking['tanggal_booking'], 'eeee, d MMMM YYYY')) ?>
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
                    Untuk pertanyaan, hubungi kami di <a href="mailto:globalservice4545@gmail.com">globalservice4545@gmail.com</a> atau WhatsApp <a href="https://wa.me/6287881620835">+62 878 8162 0835</a>
                </p>

                <div class="text-center mt-4">
                    <?php if (session()->get('logged_in_admin')): ?>
                        <a href="<?= base_url('admin/dashboard') ?>" class="btn btn-primary-action">
                            <i class="fas fa-tasks"></i> Kelola Booking Pelanggan
                        </a>
                    <?php elseif (session()->get('logged_in_pelanggan')): ?>
                        <a href="<?= base_url('booking/saya') ?>" class="btn btn-primary-action">
                            <i class="fas fa-list"></i> Booking Saya
                        </a>
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
    /* Style di sini tetap sama seperti sebelumnya, tidak ada perubahan */
    .booking-success-container {
        max-width: 800px;
        margin: 2rem auto;
        padding: 1rem;
    }

    .success-card {
        background-color: #ffffff;
        border: 1px solid #dee2e6;
        border-radius: 10px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
    }

    .success-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
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
        color: #28a745 !important;
    }

    .card-body {
        padding: 2rem 2.5rem;
        color: #212529;
    }

    .lead {
        font-size: 1.15rem;
        font-weight: 400;
        color: #212529;
    }

    .booking-details {
        background-color: #f8f9fa;
        padding: 1.5rem;
        border-radius: 6px;
        margin-top: 1.5rem;
        border: 1px solid #dee2e6;
    }

    .details-title,
    .summary-title {
        color: #0a84ff;
        margin-bottom: 1rem;
        font-size: 1.25rem;
        font-weight: 600;
        border-bottom: 1px solid #dee2e6;
        padding-bottom: 0.5rem;
    }

    .detail-grid {
        display: grid;
        grid-template-columns: auto 1fr;
        gap: 0.5rem 1rem;
        align-items: center;
    }

    .detail-label {
        font-weight: 600;
        color: #212529;
    }

    .detail-value {
        color: #6c757d;
    }

    .layanan-summary .list-group-item {
        background-color: transparent;
        border-color: #e9ecef;
        color: #212529;
        padding: 0.75rem 0;
        font-size: 0.95rem;
    }

    .layanan-summary .list-group-item span {
        color: #343a40;
        font-weight: 500;
    }

    .instructions {
        font-size: 0.9rem;
        line-height: 1.6;
        color: #6c757d;
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
        border-top: 1px solid #dee2e6;
    }

    .btn-primary-action {
        background-color: #0a84ff;
        color: white !important;
        padding: 0.75rem 1.5rem;
        text-decoration: none;
        border-radius: 5px;
        font-weight: 600;
        border: none;
        display: inline-flex;
        align-items: center;
    }

    .btn-primary-action:hover {
        background-color: #006ecc;
    }

    .btn-primary-action i {
        margin-right: 0.5rem;
    }

    .text-center {
        text-align: center !important;
    }

    .fa-3x {
        font-size: 3em;
    }

    .text-danger {
        color: #dc3545 !important;
    }
</style>
<?= $this->endSection() ?>