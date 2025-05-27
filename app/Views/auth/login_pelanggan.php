<?= $this->extend('template/layout') // Menggunakan layout dari app/Views/template/layout.php ?>

<?= $this->section('title') ?>
Login Pelanggan - Global Service Audio
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container">
    <div class="form-container">
        <h2>Login Pelanggan</h2>

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
        <?php if(session()->getFlashdata('info')): ?>
            <div class="alert alert-info" role="alert">
                <?= session()->getFlashdata('info') ?>
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
                <strong>Oops! Ada kesalahan:</strong>
                <ul>
                    <?php foreach ($validation->getErrors() as $error) : ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="<?= url_to('login_pelanggan_process') ?>" method="post">
            <?= csrf_field() ?>

            <div class="form-group">
                <label for="email_pelanggan">Alamat Email</label>
                <input type="email" name="email_pelanggan" id="email_pelanggan" class="form-control" value="<?= old('email_pelanggan') ?>" required>
            </div>

            <div class="form-group">
                <label for="password_pelanggan">Password</label>
                <input type="password" name="password_pelanggan" id="password_pelanggan" class="form-control" required>
            </div>

            <button type="submit" class="form-button">Login</button>
        </form>
        <p style="text-align: center; margin-top: 1.5rem; color: #c0c0c0;">
            Belum punya akun? <a href="<?= url_to('register_pelanggan_show') ?>" style="color: #0a84ff; font-weight: 600; text-decoration: none;">Registrasi di sini</a>
        </p>
        
        <hr style="margin-top: 2rem; margin-bottom: 1.5rem; border: 0; border-top: 1px solid #333;">

        <p style="text-align: center; color: #c0c0c0; font-size: 0.9rem;"> <?php // Ukuran font disamakan jika perlu ?>
            <a href="<?= url_to('login_admin_show') ?>" style="color: #0a84ff; font-weight: 600; text-decoration: none;">Login sebagai Admin</a> <?php // Style disamakan ?>
        </p>
    </div>
</div>
<?= $this->endSection() ?>

<?php // Hapus section pageStyles jika tidak ada style spesifik lain untuk halaman ini ?>
<?php /*
<?= $this->section('pageStyles') ?>
<style>
    // Tidak perlu style di sini jika sudah menggunakan inline atau di style.css utama
</style>
<?= $this->endSection() ?>
*/ ?>