<?= $this->extend('template/layout') // Menggunakan layout dari app/Views/template/layout.php ?>

<?= $this->section('title') ?>
Registrasi Pelanggan - Global Service Audio
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container">
    <div class="form-container">
        <h2>Registrasi Akun Pelanggan Baru</h2>

        <?php if(session()->getFlashdata('success')): ?>
            <div class="alert alert-success" role="alert">
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>
        <?php if(session()->getFlashdata('error')): ?>
            <div class="alert alert-danger" role="alert">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <?php 
        $validation = session()->getFlashdata('validation');
        if(!$validation && isset($validator)) { 
            $validation = $validator;
        }
        ?>

        <?php if($validation): ?>
            <div class="alert alert-danger validation-errors" role="alert">
                <strong>Oops! Ada kesalahan saat validasi:</strong>
                <ul>
                    <?php foreach ($validation->getErrors() as $error) : ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="<?= url_to('AuthController::prosesRegistrasiPelanggan') ?>" method="post">
            <?= csrf_field() // Proteksi CSRF ?>

            <div class="form-group">
                <label for="nama_pelanggan">Nama Lengkap</label>
                <input type="text" name="nama_pelanggan" id="nama_pelanggan" class="form-control" value="<?= old('nama_pelanggan') ?>" required>
            </div>

            <div class="form-group">
                <label for="no_hp">Nomor Handphone</label>
                <input type="text" name="no_hp" id="no_hp" class="form-control" value="<?= old('no_hp') ?>" placeholder="Contoh: 08123456789" required>
            </div>

            <div class="form-group">
                <label for="email_pelanggan">Alamat Email</label>
                <input type="email" name="email_pelanggan" id="email_pelanggan" class="form-control" value="<?= old('email_pelanggan') ?>" required>
            </div>

            <div class="form-group">
                <label for="password_pelanggan">Password</label>
                <input type="password" name="password_pelanggan" id="password_pelanggan" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="konfirmasi_password">Konfirmasi Password</label>
                <input type="password" name="konfirmasi_password" id="konfirmasi_password" class="form-control" required>
            </div>

            <button type="submit" class="form-button">Registrasi</button>
        </form>
        <p style="text-align: center; margin-top: 1.5rem; color: #c0c0c0;">
            Sudah punya akun? <a href="<?= url_to('AuthController::tampilkanLoginPelanggan') ?>" style="color: #0a84ff; font-weight: 600;">Login di sini</a>
        </p>
    </div>
</div>
<?= $this->endSection() ?>