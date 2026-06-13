<?= $this->extend('template/layout') // Menggunakan layout utama Anda 
?>

<?= $this->section('title') ?>
<?= esc($title ?? 'Booking Layanan') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container">
    <div class="form-container booking-form-container">
        <h2 class="text-center mb-4"><?= esc($title ?? 'Booking Layanan') ?></h2>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success" role="alert"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger" role="alert"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>
        <?php $validation_errors = session()->getFlashdata('validation_errors') ?? ($validation ?? null); ?>
        <?php if (isset($validation_errors) && $validation_errors->getErrors()): ?>
            <div class="alert alert-danger validation-errors" role="alert">
                <strong>Periksa kembali input Anda:</strong>
                <ul>
                    <?php foreach ($validation_errors->getErrors() as $error): ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="<?= url_to('booking_form_process') ?>" method="post">
            <?= csrf_field() ?>

            <?php
            $idPelangganSaatIni = '';
            $namaPelangganSaatIni = 'Pelanggan';
            if (session()->get('logged_in_pelanggan')) {
                $idPelangganSaatIni = session()->get('id_pelanggan');
                $namaPelangganSaatIni = session()->get('nama_pelanggan');
            }
            ?>

            <?php if (session()->get('logged_in_admin')): ?>
                <div class="form-group mb-3">
                    <label for="id_pelanggan_booking">Pilih Pelanggan untuk Booking</label>
                    <select name="id_pelanggan_booking" id="id_pelanggan_booking" class="form-control <?= (isset($validation_errors) && $validation_errors->hasError('id_pelanggan_booking')) ? 'is-invalid' : '' ?>" required>
                        <option value="">-- Pilih Pelanggan --</option>
                        <?php if (!empty($daftar_pelanggan) && is_array($daftar_pelanggan)): ?>
                            <?php foreach ($daftar_pelanggan as $pelanggan): ?>
                                <option value="<?= esc($pelanggan['id_pelanggan']) ?>" <?= (old('id_pelanggan_booking') == $pelanggan['id_pelanggan']) ? 'selected' : '' ?>>
                                    <?= esc($pelanggan['nama_pelanggan']) ?> (ID: <?= esc($pelanggan['id_pelanggan']) ?>)
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <?php if (isset($validation_errors) && $validation_errors->hasError('id_pelanggan_booking')): ?>
                        <div class="invalid-feedback"><?= $validation_errors->getError('id_pelanggan_booking') ?></div>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <input type="hidden" name="id_pelanggan_booking" value="<?= esc($idPelangganSaatIni) ?>">
                <div class="form-group mb-3">
                    <label for="nama_pemesan">Nama Pemesan</label>
                    <input type="text" id="nama_pemesan" class="form-control form-control-readonly" value="<?= esc($namaPelangganSaatIni) ?>" readonly>
                </div>
            <?php endif; ?>

            <div class="form-group mb-3">
                <label for="tanggal_booking">Pilih Tanggal Booking</label>
                <input type="date" name="tanggal_booking" id="tanggal_booking" class="form-control <?= (isset($validation_errors) && $validation_errors->hasError('tanggal_booking')) ? 'is-invalid' : '' ?>" value="<?= old('tanggal_booking') ?>" required>
                <?php if (isset($validation_errors) && $validation_errors->hasError('tanggal_booking')): ?>
                    <div class="invalid-feedback"><?= $validation_errors->getError('tanggal_booking') ?></div>
                <?php endif; ?>
            </div>

            <div class="form-group mb-4">
                <label>Pilih Layanan (Bisa lebih dari satu):</label>
                <?php if (!empty($layanan_tersedia) && is_array($layanan_tersedia)): ?>
                    <div class="layanan-checkbox-group <?= (isset($validation_errors) && $validation_errors->hasError('layanan_ids')) ? 'is-invalid-group' : '' ?>">
                        <?php foreach ($layanan_tersedia as $layanan): ?>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="layanan_ids[]" value="<?= esc($layanan['id_layanan']) ?>" id="layanan_<?= esc($layanan['id_layanan']) ?>"
                                    <?= (is_array(old('layanan_ids')) && in_array($layanan['id_layanan'], old('layanan_ids'))) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="layanan_<?= esc($layanan['id_layanan']) ?>">
                                    <?= esc($layanan['nama_layanan']) ?> (Rp <?= number_format(esc($layanan['harga']), 0, ',', '.') ?>)
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <?php if (isset($validation_errors) && $validation_errors->hasError('layanan_ids')): ?>
                        <div class="invalid-feedback d-block"><?= $validation_errors->getError('layanan_ids') ?></div>
                    <?php endif; ?>
                <?php else: ?>
                    <p class="text-muted">Saat ini tidak ada layanan yang tersedia.</p>
                <?php endif; ?>
            </div>

            <button type="submit" class="form-button w-100 mb-3"><i class="fas fa-calendar-check"></i> Buat Booking</button>
            <a href="<?= url_to('home') ?>" class="btn btn-secondary-action w-100"><i class="fas fa-times"></i> Batal</a>
        </form>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('pageScripts') ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tanggalBookingInput = document.getElementById('tanggal_booking');
        if (tanggalBookingInput) {
            const today = new Date();
            const year = today.getFullYear();
            let month = today.getMonth() + 1;
            let day = today.getDate();

            month = month < 10 ? '0' + month : month;
            day = day < 10 ? '0' + day : day;

            const todayFormatted = `${year}-${month}-${day}`;
            tanggalBookingInput.setAttribute('min', todayFormatted);
        }
    });
</script>
<?= $this->endSection() ?>

<?= $this->section('pageStyles') ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<style>
    /* PERBAIKAN 1 (CSS):
       - Menambahkan aturan CSS untuk class `form-control-readonly` 
         agar sesuai dengan permintaan Anda.
    */
    .form-control-readonly {
        background-color: #ffffff !important;
        /* Latar belakang putih */
        border: 1px solid #000000 !important;
        /* Border hitam */
        color: #6c757d !important;
        /* Warna teks abu-abu agar terlihat seperti non-aktif */
        cursor: not-allowed;
        /* Mengubah cursor untuk menandakan field tidak bisa diubah */
    }
</style>
<?= $this->endSection() ?>