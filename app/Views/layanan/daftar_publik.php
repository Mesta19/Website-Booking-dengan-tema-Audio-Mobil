<?= $this->extend('template/layout') // Menggunakan layout utama Anda ?>

<?= $this->section('title') ?>
    <?= esc($title ?? 'Layanan Kami') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container">
    <div class="layanan-publik-container">
        <h1 class="page-title-public"><?= esc($title ?? 'Layanan Kami') ?></h1>
        <p class="page-subtitle-public">Temukan berbagai layanan berkualitas untuk kebutuhan audio mobil Anda.</p>

        <?php if (!empty($layanan_tersedia) && is_array($layanan_tersedia)): ?>
            <div class="layanan-grid-public">
                <?php
                    $direktoriGambar = ROOTPATH . 'public/gambar_layanan/';
                    $daftarGambar = glob($direktoriGambar . '*.{jpg,jpeg,png,gif}', GLOB_BRACE);
                    $jumlahGambar = count($daftarGambar);
                    $gambarSudahDipakai = []; // Array untuk melacak gambar yang sudah digunakan

                    if (empty($daftarGambar)) {
                        $gambarDefault = 'https://via.placeholder.com/300/cccccc/ffffff?Text=Tidak+Ada+Gambar'; // Gambar placeholder jika tidak ada gambar di direktori
                    }
                ?>
                <?php foreach ($layanan_tersedia as $layanan_item): ?>
                    <div class="layanan-card-public">
                        <?php if (!empty($daftarGambar)): ?>
                            <?php
                                $gambarAcak = '';
                                $randomIndex = -1;
                                // Cari gambar acak yang belum digunakan
                                for ($i = 0; $i < 10; $i++) { // Batasi percobaan untuk menghindari infinite loop jika gambar lebih sedikit dari layanan
                                    $randomIndex = array_rand($daftarGambar);
                                    if (!in_array($randomIndex, $gambarSudahDipakai)) {
                                        $gambarAcak = base_url('gambar_layanan/' . basename($daftarGambar[$randomIndex]));
                                        $gambarSudahDipakai[] = $randomIndex;
                                        break;
                                    }
                                }
                                // Jika setelah beberapa percobaan tidak menemukan gambar unik, gunakan gambar acak terakhir yang didapatkan
                                if (empty($gambarAcak) && !empty($daftarGambar)) {
                                    $randomIndex = array_rand($daftarGambar);
                                    $gambarAcak = base_url('gambar_layanan/' . basename($daftarGambar[$randomIndex]));
                                }
                            ?>
                            <?php if (!empty($gambarAcak)): ?>
                                <img src="<?= $gambarAcak ?>" alt="<?= esc($layanan_item['nama_layanan']) ?>" class="card-img-top" style="width: 100%; height: auto; border-top-left-radius: 8px; border-top-right-radius: 8px; display: block;">
                            <?php else: ?>
                                <img src="<?= $gambarDefault ?>" alt="Tidak Ada Gambar" class="card-img-top" style="width: 100%; height: auto; border-top-left-radius: 8px; border-top-right-radius: 8px; display: block;">
                            <?php endif; ?>
                        <?php else: ?>
                            <img src="<?= $gambarDefault ?>" alt="Tidak Ada Gambar" class="card-img-top" style="width: 100%; height: auto; border-top-left-radius: 8px; border-top-right-radius: 8px; display: block;">
                        <?php endif; ?>
                        <div class="card-content">
                            <h3><?= esc($layanan_item['nama_layanan']) ?></h3>
                            <p class="harga">Rp <?= number_format(esc($layanan_item['harga']), 0, ',', '.') ?></p>
                            <?php // Anda bisa menambahkan deskripsi singkat di sini jika ada field-nya di database ?>
                            <?php /*
                            <p class="deskripsi-singkat">
                                <?= character_limiter(esc($layanan_item['deskripsi_singkat'] ?? 'Deskripsi singkat layanan...'), 100) ?>
                            </p>
                            */ ?>
                        </div>
                        <?php // Bagian card-action dihapus ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="text-center py-5"> <?php // Padding atas bawah jika kosong ?>
                <i class="fas fa-info-circle fa-3x text-muted mb-3"></i>
                <p class="text-muted fs-5">Saat ini belum ada layanan yang tersedia.</p>
                <p class="text-muted">Silakan cek kembali nanti atau hubungi kami untuk informasi lebih lanjut.</p>
            </div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('pageStyles') // Untuk CSS tambahan jika perlu ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .layanan-publik-container {
            padding: 2rem 0;
        }
        .page-title-public {
            color: #0a84ff;
            text-align: center;
            margin-bottom: 0.75rem;
            font-size: 2.5rem;
            font-weight: 700;
        }
        .page-subtitle-public {
            text-align: center;
            color: #c0c0c0;
            margin-bottom: 3rem;
            font-size: 1.15rem;
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
        }
        .layanan-grid-public {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); /* Ukuran kartu disesuaikan */
            gap: 2rem; /* Jarak antar kartu */
        }
        .layanan-card-public {
            background-color: #1e1e1e; /* Warna latar kartu */
            padding: 0; /* Padding diatur oleh content */
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.25);
            border: 1px solid #2c2c2c; /* Border kartu lebih halus */
            display: flex;
            flex-direction: column;
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        }
        .layanan-card-public:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(10, 132, 255, 0.2);
        }
        .layanan-card-public .card-content {
            padding: 1.75rem; /* Padding internal konten kartu */
            flex-grow: 1; /* Konten mengisi ruang */
        }
        .layanan-card-public h3 {
            color: #0a84ff;
            font-size: 1.4rem; /* Ukuran font nama layanan */
            font-weight: 600;
            margin-bottom: 0.75rem;
        }
        .layanan-card-public .harga {
            font-size: 1.25rem; /* Ukuran font harga */
            font-weight: 700;
            color: #e0e0e0;
            margin-bottom: 1rem;
        }
        .layanan-card-public .deskripsi-singkat {
            font-size: 0.9rem;
            color: #adb5bd;
            margin-bottom: 1.25rem;
            line-height: 1.5;
        }
        .text-muted { color: #888 !important; }
        .fs-5 { font-size: 1.25rem !important; }
        .fa-3x { font-size: 3em !important; }
        .mb-3 { margin-bottom: 1rem !important; }
        .py-5 { padding-top: 3rem !important; padding-bottom: 3rem !important; }
    </style>
<?= $this->endSection() ?>