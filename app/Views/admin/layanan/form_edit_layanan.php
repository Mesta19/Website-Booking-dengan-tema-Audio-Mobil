<?= $this->extend('template/layout') // Sesuaikan path layout ?>

<?= $this->section('title') ?>
    <?= esc($title ?? 'Edit Layanan') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container">
    <div class="form-container admin-form">
        <h2><?= esc($title ?? 'Edit Layanan') ?></h2>

        <?php if(session()->getFlashdata('error')): ?>
            <div class="alert alert-danger" role="alert"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>

        <?php if(isset($validation) && $validation->getErrors()): ?>
            <div class="alert alert-danger validation-errors" role="alert">
                <strong>Periksa kembali input Anda:</strong>
                <ul>
                <?php foreach ($validation->getErrors() as $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="<?= url_to('admin_layanan_update', $layanan['id_layanan']) ?>" method="post">
            <?= csrf_field() ?>

            <div class="form-group">
                <label for="nama_layanan">Nama Layanan</label>
                <input type="text" name="nama_layanan" id="nama_layanan" class="form-control <?= (isset($validation) && $validation->hasError('nama_layanan')) ? 'is-invalid' : '' ?>" value="<?= old('nama_layanan', $layanan['nama_layanan']) ?>">
                 <?php if(isset($validation) && $validation->hasError('nama_layanan')): ?>
                    <div class="invalid-feedback"><?= $validation->getError('nama_layanan') ?></div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="harga">Harga (Rp)</label>
                <input type="number" name="harga" id="harga" class="form-control <?= (isset($validation) && $validation->hasError('harga')) ? 'is-invalid' : '' ?>" value="<?= old('harga', $layanan['harga']) ?>" min="0" step="any" placeholder="Contoh: 75000">
                <?php if(isset($validation) && $validation->hasError('harga')): ?>
                    <div class="invalid-feedback"><?= $validation->getError('harga') ?></div>
                <?php endif; ?>
            </div>

            <button type="submit" class="form-button"><i class="fas fa-save"></i> Update</button>
            <a href="<?= url_to('admin_layanan_index') ?>" class="btn btn-secondary-action" style="margin-top:10px; display:inline-block;"><i class="fas fa-times"></i> Batal</a>
        </form>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('pageStyles') ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        /* Style dari style.css utama atau pembahasan sebelumnya */
        .admin-form { max-width: 700px; margin: 2rem auto; }
        .btn-secondary-action { background-color: #6c757d; color: white !important; padding: 0.75rem; text-decoration: none; border-radius: 4px; text-align: center; border:none;}
        .btn-secondary-action:hover { background-color: #5a6268; }
        .is-invalid { border-color: #dc3545 !important; }
        .invalid-feedback { color: #dc3545; font-size: 0.875em; margin-top: .25rem; display: block; }
        .btn i { margin-right: 4px; }
    </style>
<?= $this->endSection() ?>