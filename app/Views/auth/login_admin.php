<?= $this->extend('template/layout') // Menggunakan layout dari app/Views/template/layout.php ?>

<?= $this->section('title') ?>
Login Admin - Global Service Audio
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container">
    <div class="form-container">
        <h2>Login Admin</h2>

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

        <form action="<?= url_to('login_admin_process') // Menggunakan nama rute untuk proses login admin ?>" method="post">
            <?= csrf_field() // Proteksi CSRF ?>

            <div class="form-group">
                <label for="username_admin">Username Admin</label>
                <input type="text" name="username_admin" id="username_admin" class="form-control" value="<?= old('username_admin') ?>" required>
            </div>

            <div class="form-group">
                <label for="password_admin">Password Admin</label>
                <input type="password" name="password_admin" id="password_admin" class="form-control" required>
            </div>

            <button type="submit" class="form-button">Login</button>
        </form>
        
        <hr style="margin-top: 2rem; margin-bottom: 1.5rem; border: 0; border-top: 1px solid #333;"> <?php // Garis pemisah ?>

        <p style="text-align: center; color: #c0c0c0; font-size: 0.9rem;">
            Bukan Admin? <a href="<?= url_to('login_pelanggan_show') // Menggunakan nama rute untuk tampilkan login pelanggan ?>" style="color: #0a84ff; font-weight: 600; text-decoration: none;">Login sebagai Pelanggan</a> <?php // Style disamakan ?>
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