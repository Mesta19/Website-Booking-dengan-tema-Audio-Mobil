<?= $this->extend('template/layout') // Menggunakan layout utama Anda ?>

<?= $this->section('title') ?>
    <?= esc($title ?? 'Beranda - Global Service Audio') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="hero-section">
    <div class="container text-center">
        <h1 class="hero-title">Global Service Audio Mobil</h1>
        <p class="hero-subtitle">Solusi Audio Mobil Profesional untuk Pengalaman Berkendara Terbaik Anda.
            Global Service Audio Mobil bekerja pada layanan Jasa dan Reparasi untuk segala Audio Mobil.
        Kami juga menyediakan produk audio mobil pada online marketplace kami.</p>
        <a href="<?= url_to('layanan_publik_daftar') // Pastikan nama rute ini ada ?>" class="btn btn-hero-primary">Lihat Layanan Kami</a>
        <a href="https://maps.app.goo.gl/pRWJi3bhmNkAQSr76" target="_blank" class="btn btn-hero-primary ml-2">
            <i class="fas fa-map-marker-alt mr-2"></i> Lokasi Kami
        </a>
        <?php if(session()->get('logged_in_pelanggan')): ?>
            <a href="<?= url_to('booking_form_show') // Pastikan nama rute ini ada ?>" class="btn btn-hero-secondary mt-3">Booking Sekarang</a>
        <?php elseif(!session()->get('logged_in_admin') && !session()->get('logged_in_pelanggan')): ?>
            <a href="<?= url_to('login_pelanggan_show') // Pastikan nama rute ini ada ?>" class="btn btn-hero-secondary mt-3">Login & Booking</a>
        <?php endif; ?>
         <div class="scroll-down-indicator">
            <i class="fas fa-angle-double-down"></i>
        </div>
        </div>
    </div>
</div>

<div class="container page-section">
    <div class="section-title text-center">
        <h2>Mengapa Memilih Kami?</h2>
        <p>Kami menawarkan kualitas dan layanan yang berpengalaman.</p>
    </div>
    <div class="features-grid">
        <div class="feature-item">
            <div class="feature-icon"><i class="fas fa-tools"></i></div>
            <h3>Teknisi Profesional</h3>
            <p>Tim kami terdiri dari teknisi berpengalaman dan bersertifikat.</p>
        </div>
        <div class="feature-item">
            <div class="feature-icon"><i class="fas fa-certificate"></i></div>
            <h3>Produk Berkualitas</h3>
            <p>Hanya menggunakan produk audio original dan berkualitas tinggi.</p>
        </div>
        <div class="feature-item">
            <div class="feature-icon"><i class="fas fa-headset"></i></div>
            <h3>Layanan Pelanggan</h3>
            <p>Kepuasan Anda adalah prioritas utama kami.</p>
        </div>
    </div>
</div>

<div class="container page-section">
    <h2 class="text-center mb-4">Belanja Produk di Marketplace kami :</h2>
    <div class="text-center">
        <a href="https://shopee.co.id/carstereoaudio" target="_blank" class="online-shop-button shopee-button mr-2">
            <i class="fab fa-shopify mr-2"></i> Shopee (Car Audio Stereo)
        </a>
        <a href="https://www.tokopedia.com/caraudiostereo/product" target="_blank" class="online-shop-button tokopedia-button">
            <i class="fas fa-shopping-bag mr-2"></i> Tokopedia (Car Audio Stereo)
        </a>
    </div>
</div>

<div class="container page-section">
    <div class="text-center">
        <h2>Hubungi Kami</h2>
        <p class="mb-4">Ada pertanyaan atau ingin konsultasi? Jangan ragu untuk menghubungi kami.</p>
        <a href="mailto:globalservice4545@gmail.com" class="btn btn-contact"><i class="fas fa-envelope"></i> Email Kami</a>
        <a href="https://wa.me/6287881620835" target="_blank" class="btn btn-contact whatsapp"><i class="fab fa-whatsapp"></i> WhatsApp Kami</a>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('pageStyles') ?>
    <?php // Jika Anda belum include Font Awesome di layout utama, tambahkan di sini atau di layout ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .hero-section {
            background: linear-gradient(rgba(10, 132, 255, 0.85), rgba(0, 95, 187, 0.85)), url('<?= base_url('images/hero-background.jpg') ?>') no-repeat center center/cover; /* Ganti dengan path gambar Anda */
            color: white;
            padding: 6rem 0;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 70vh; /* Tinggi minimal hero section */
        }
    </style>
<?= $this->endSection() ?>