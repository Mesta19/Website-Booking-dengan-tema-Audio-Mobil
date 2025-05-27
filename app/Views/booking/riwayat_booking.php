<?= $this->extend('template/layout') // Menggunakan layout utama Anda ?>

<?= $this->section('title') ?>
    <?= esc($title ?? 'Riwayat Booking Saya') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container">
    <div class="riwayat-booking-container">
        <h1 class="page-title-public"><?= esc($title ?? 'Riwayat Booking Saya') ?></h1>

        <?php if(session()->getFlashdata('success_booking_delete')): ?>
            <div class="alert alert-success" role="alert"><?= session()->getFlashdata('success_booking_delete') ?></div>
        <?php endif; ?>
        <?php if(session()->getFlashdata('error_booking_delete')): ?>
            <div class="alert alert-danger" role="alert"><?= session()->getFlashdata('error_booking_delete') ?></div>
        <?php endif; ?>
        <?php if(session()->getFlashdata('success')): ?>
            <div class="alert alert-success" role="alert"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>
        <?php if(session()->getFlashdata('error')): ?>
            <div class="alert alert-danger" role="alert"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>

        <?php if (!empty($bookings) && is_array($bookings)): ?>
            <div class="booking-list-container">
                <?php foreach ($bookings as $booking): ?>
                    <div class="booking-card-item">
                        <div class="booking-card-header">
                            <div class="booking-summary-header">
                                <span><strong>ID Booking:</strong> #<?= esc($booking['id_booking']) ?></span>
                                <span>
                                    <strong>Tgl. Booking:</strong>
                                    <?php if (function_exists('format_indo_datetime')): ?>
                                        <?= esc(format_indo_datetime($booking['tanggal_booking'], 'd MMMM YYYY')) ?>
                                    <?php else: ?>
                                        <?= esc(date('d M Y', strtotime($booking['tanggal_booking']))) ?>
                                    <?php endif; ?>
                                </span>
                                <span class="total-harga-header"><strong>Total:</strong> Rp <?= number_format(esc($booking['total_harga']), 0, ',', '.') ?></span>
                            </div>
                        </div>
                        <div class="booking-card-body">
                            <h5 class="detail-layanan-title">Detail Layanan Dipesan:</h5>
                            <?php if (!empty($booking['detail_layanan_items']) && is_array($booking['detail_layanan_items'])): ?>
                                <ul class="list-group list-group-flush mb-3">
                                    <?php foreach ($booking['detail_layanan_items'] as $layanan_item): ?>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <?= esc($layanan_item['nama_layanan']) ?>
                                            <span>Rp <?= number_format(esc($layanan_item['harga']), 0, ',', '.') ?></span>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php else: ?>
                                <p class="text-muted">Tidak ada detail layanan untuk booking ini.</p>
                            <?php endif; ?>

                            <?php
                            $bisaDibatalkan = true;
                            // Contoh logika yang lebih kompleks:
                            // $tanggalBookingObj = new DateTime($booking['tanggal_booking']);
                            // $sekarang = new DateTime();
                            // if ($tanggalBookingObj <= $sekarang) {
                            //      $bisaDibatalkan = false;
                            // }
                            ?>

                            <?php if($bisaDibatalkan): ?>
                            <div class="text-end mt-3">
                                <button type="button" class="btn btn-sm btn-danger btn-cancel-booking"
                                    data-idbooking="<?= esc($booking['id_booking']) ?>"
                                    data-info="ID #<?= esc($booking['id_booking']) ?> Tgl. <?= function_exists('format_indo_datetime') ? esc(format_indo_datetime($booking['tanggal_booking'], 'd M Y')) : esc(date('d M Y', strtotime($booking['tanggal_booking']))) ?>">
                                    <i class="fas fa-times-circle"></i> Batalkan Booking Ini
                                </button>
                                <form id="cancel-booking-form-<?= esc($booking['id_booking']) ?>" action="<?= url_to('pelanggan_booking_hapus', $booking['id_booking']) ?>" method="post" style="display: none;">
                                    <?= csrf_field() ?>
                                </form>
                            </div>
                            <?php else: ?>
                            <p class="text-muted text-end mt-3"><small>Booking ini sudah tidak dapat dibatalkan.</small></p>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                <p class="text-muted fs-5">Anda belum memiliki riwayat booking.</p>
                <a href="<?= url_to('booking_form_show') ?>" class="btn btn-primary-action mt-3">
                    <i class="fas fa-plus"></i> Buat Booking Sekarang
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('pageStyles') ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .riwayat-booking-container {
            padding: 2rem 0;
        }
        .page-title-public {
            color: #0a84ff;
            text-align: center;
            margin-bottom: 2.5rem;
            font-size: 2.2rem;
            font-weight: 600;
        }

        .booking-card-item {
            background-color: #1e1e1e;
            border: 1px solid #383838;
            margin-bottom: 1.5rem;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }

        .booking-card-header {
            background-color: #007bff;
            color: white;
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #0056b3;
        }

        .booking-summary-header {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            font-size: 0.9rem;
        }
        .booking-summary-header span {
            margin-right: 1rem;
            margin-bottom: 0.25rem;
        }
        .booking-summary-header span strong {
            color: white;
        }
        .booking-summary-header .total-harga-header {
            font-weight: 700;
            color: white;
            font-size: 0.95rem;
        }

        .booking-card-body {
            padding: 1.5rem;
            background-color: #22272e;
            color: #c0c0c0;
        }

        .detail-layanan-title {
            font-size: 1.1rem;
            color: #0a84ff;
            margin-bottom: 1rem;
            font-weight: 600;
            padding-bottom: 0.5rem;
            border-bottom: 1px dashed #383838;
        }

        .list-group-item {
            background-color: transparent;
            border-color: #303030;
            color: #c0c0c0;
            padding: 0.75rem 0;
            font-size: 0.9rem;
        }
        .list-group-item:last-child {
            border-bottom: none;
        }
        .list-group-item span {
            font-weight: 500;
            color: #e0e0e0;
        }

        .btn-cancel-booking {
            background-color: #dc3545;
            border-color: #dc3545;
            color: white !important;
            padding: 0.6rem 1.2rem;
            font-size: 0.85rem;
            border-radius: 5px;
            transition: all 0.2s ease-in-out;
            font-weight: 500;
        }
        .btn-cancel-booking:hover {
            background-color: #c82333;
            border-color: #bd2130;
            transform: translateY(-1px);
            box-shadow: 0 2px 5px rgba(220,53,69,0.4);
        }
        .btn-cancel-booking i {
            margin-right: 0.5rem;
        }

        .alert {
            margin-bottom: 1.5rem;
            border-left-width: 5px;
            border-radius: 5px;
        }
        .text-muted { color: #888 !important; }
        .text-end { text-align: right !important; }

    </style>
<?= $this->endSection() ?>

<?= $this->section('pageScripts') ?>
    <script>
        document.querySelectorAll('.btn-cancel-booking').forEach(button => {
            button.addEventListener('click', function() {
                const bookingId = this.getAttribute('data-idbooking');
                const bookingInfo = this.getAttribute('data-info');
                const form = document.getElementById('cancel-booking-form-' + bookingId);

                Swal.fire({
                    title: 'Konfirmasi Pembatalan',
                    html: `Anda yakin ingin membatalkan booking:<br><strong>${bookingInfo}</strong>?<br><small style="color: #f8bb86;">(Aksi ini tidak dapat diurungkan!)</small>`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: '<i class="fas fa-trash-alt"></i> Ya, Batalkan!',
                    cancelButtonText: '<i class="fas fa-times"></i> Tidak',
                    reverseButtons: true,
                    customClass: {
                        popup: 'swal2-dark-popup',
                        title: 'swal2-dark-title',
                        htmlContainer: 'swal2-dark-html-container',
                        confirmButton: 'btn btn-danger swal-confirm-button',
                        cancelButton: 'btn btn-primary swal-cancel-button'
                    },
                    buttonsStyling: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        if (form) {
                            form.submit();
                        } else {
                            console.error('Form untuk cancel booking tidak ditemukan: cancel-booking-form-' + bookingId);
                            Swal.fire('Error', 'Tidak bisa memproses pembatalan, form tidak ditemukan.', 'error');
                        }
                    }
                });
            });
        });
    </script>
    <style>
        .swal2-dark-popup { background-color: #282c34 !important; color: #abb2bf !important; border-radius: 8px !important;}
        .swal2-dark-title { color: #0a84ff !important; }
        .swal2-dark-html-container { color: #abb2bf !important; }

        .swal-confirm-button, .swal-cancel-button {
            margin: 0 5px !important;
            padding: 0.6em 1.2em !important;
            font-weight: 600 !important;
            border-radius: 5px !important;
        }
        .swal-confirm-button i, .swal-cancel-button i { margin-right: 5px; }
    </style>
<?= $this->endSection() ?>