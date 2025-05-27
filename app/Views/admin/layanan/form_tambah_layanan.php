<?= $this->extend('template/layout') // Pastikan path layout ini benar ?>

<?= $this->section('title') ?>
    <?= esc($title ?? 'Tambah Layanan') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container">
    <div class="form-container admin-form">
        <h2><?= esc($title ?? 'Tambah Layanan Baru') ?></h2>

        <?php /* Menampilkan error umum dari controller (bukan error validasi form) */ ?>
        <?php if(session()->getFlashdata('error')): ?>
            <div class="alert alert-danger" role="alert"><?= esc(session()->getFlashdata('error')) ?></div>
        <?php endif; ?>
        
         <?php 
        $validation = \Config\Services::validation(); 
        // --- TAMBAHKAN BLOK DEBUG INI ---
        if (!empty($validation->getErrors())) {
            echo '<div class="alert alert-info"><strong>Debug - All Validation Errors:</strong><pre>';
            print_r($validation->getErrors());
            echo '</pre></div>';
        } else {
            // echo '<div class="alert alert-warning">Debug - No validation errors found in session for this request.</div>';
        }
        // --- AKHIR BLOK DEBUG ---
        ?>

        <?php /* Blok untuk menampilkan semua error validasi di bagian atas form */ ?>
        <?php if ($validation->getErrors()): // Cek apakah ada error validasi ?>
            <div class="alert alert-danger validation-errors" role="alert">
                <strong>Mohon perbaiki kesalahan input berikut:</strong>
                <ul>
                <?php foreach ($validation->getErrors() as $error): // Loop semua error ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="<?= url_to('admin_layanan_simpan') // Pastikan nama rute ini benar dan mengarah ke metode controller yang melakukan validasi ?>" method="post">
            <?= csrf_field() ?>

            <div class="form-group">
                <label for="nama_layanan">Nama Layanan</label>
                <input type="text" name="nama_layanan" id="nama_layanan" 
                       class="form-control <?= ($validation->hasError('nama_layanan')) ? 'is-invalid' : '' ?>" 
                       value="<?= old('nama_layanan', '') // Gunakan old() untuk mengisi kembali input lama ?>">
                <?php if($validation->hasError('nama_layanan')): // Cek error spesifik untuk field 'nama_layanan' ?>
                    <div class="invalid-feedback">
                        <?= esc($validation->getError('nama_layanan')) // Tampilkan pesan error spesifik ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="harga">Harga (Rp)</label>
                <input type="number" name="harga" id="harga" 
                       class="form-control <?= ($validation->hasError('harga')) ? 'is-invalid' : '' ?>" 
                       value="<?= old('harga', '') // Gunakan old() untuk mengisi kembali input lama ?>" 
                       min="0" step="any" placeholder="Contoh: 50000">
                 <?php if($validation->hasError('harga')): // Cek error spesifik untuk field 'harga' ?>
                    <div class="invalid-feedback">
                        <?= esc($validation->getError('harga')) // Tampilkan pesan error spesifik ?>
                    </div>
                <?php endif; ?>
            </div>

            <button type="submit" class="form-button"><i class="fas fa-save"></i> Simpan</button>
            <a href="<?= url_to('admin_layanan_index') // Pastikan nama rute ini benar ?>" class="btn btn-secondary-action" style="margin-top:10px; display:inline-block;"><i class="fas fa-times"></i> Batal</a>
        </form>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('pageStyles') ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        /* Style dari style.css utama atau pembahasan sebelumnya */
        .admin-form { max-width: 700px; margin: 2rem auto; }
        .form-group { margin-bottom: 1rem; } /* Menambahkan jarak antar form-group */
        .btn-secondary-action { background-color: #6c757d; color: white !important; padding: 0.75rem; text-decoration: none; border-radius: 4px; text-align: center; border:none;}
        .btn-secondary-action:hover { background-color: #5a6268; }
        .form-control.is-invalid { border-color: #dc3545 !important; } /* Warna border merah untuk input invalid */
        .invalid-feedback { 
            color: #dc3545; 
            font-size: 0.875em; 
            margin-top: .25rem; 
            display: block; /* Pastikan pesan error tampil */
        }
        .btn i { margin-right: 4px; }
        .alert-danger.validation-errors ul { 
            margin-bottom: 0; 
            padding-left: 1.2rem; 
            list-style: disc; /* Menggunakan disc untuk bullet point yang lebih standar */
        }
         .alert-danger.validation-errors li { 
            margin-bottom: 0.25rem; /* Jarak antar item error */
        }
    </style>
<?= $this->endSection() ?>