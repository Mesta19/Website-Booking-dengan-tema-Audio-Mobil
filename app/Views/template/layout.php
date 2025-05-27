<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><?= $this->renderSection('title', 'Car Audio Service') // Judul default jika tidak di-set ?></title>
    
    <link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <?= $this->renderSection('pageStyles') ?>
</head>
<body>

   <header>
        <div class="logo-container">
            <img src="<?= base_url('logo/global-service.png') ?>" alt="Logo Audio Mobil" class="audio-mobil-logo">
        </div>
        <nav>
            <a href="<?= base_url('/') ?>">Beranda</a>
            
            <?php 
            $session = session(); // Ambil instance session
            
            // Tentukan URL dan teks untuk link Layanan berdasarkan status login
            $urlLayanan = base_url('layanan'); // Default untuk publik dan pelanggan
            $teksLayanan = 'Layanan';

            if ($session->get('logged_in_admin')) {
                $urlLayanan = base_url('admin/layanan'); // Untuk admin, arahkan ke pengelolaan layanan
                // Teks tetap 'Layanan' sesuai permintaan Anda
            }
            ?>
            <a href="<?= $urlLayanan ?>"><?= $teksLayanan ?></a> <?php // Link Layanan yang dinamis ?>
            
            <a href="<?= base_url('booking/form') ?>">Booking</a>
            
            <?php 
            if ($session->get('logged_in_pelanggan')) : ?>
                <a href="<?= base_url('booking/saya') ?>">Booking Saya</a>
                <a href="<?= url_to('AuthController::logoutPelanggan') ?>" onclick="confirmLogout(event, 'pelanggan')">Logout</a>
            <?php elseif ($session->get('logged_in_admin')) : ?>
                <a href="<?= base_url('admin/dashboard') ?>">Dashboard Admin</a>
                <?php // Link "Layanan" untuk admin sudah ditangani di atas ?>
                <a href="<?= url_to('AuthController::logoutAdmin') ?>" onclick="confirmLogout(event, 'admin')">Logout Admin</a>
            <?php else : // Jika tidak ada yang login ?>
                <a href="<?= url_to('AuthController::tampilkanLoginPelanggan') ?>">Login</a>
                <a href="<?= url_to('AuthController::tampilkanRegistrasiPelanggan') ?>">Register</a>
            <?php endif; ?>
        </nav>
    </header>

    <main class="main-content">
        <?= $this->renderSection('content') ?>
    </main>

    <footer>
        <p>Email kami : <a href="mailto:globalservice4545@gmail.com">globalservice4545@gmail.com</a> | Whats'App : <a href="https://wa.me/6287881620835">+62 878 8162 0835</a></p>
        <p>Beli Produk Kami di 
            <a href="https://shopee.co.id/carstereoaudio" target="_blank" rel="noopener">Shopee</a> |
            <a href="https://www.tokopedia.com/caraudiostereo/product" target="_blank" rel="noopener">Tokopedia</a>
        </p>
        <p>&copy; <?= date('Y') ?> Global Service Audio. Hak Cipta Dilindungi.</p>
    </footer>

    <script>
    // Fungsi konfirmasi logout yang lebih dinamis
    function confirmLogout(event, userType) {
        event.preventDefault(); // Mencegah link default redirect
        let logoutUrl = event.currentTarget.href;

        Swal.fire({
            title: 'Konfirmasi Logout',
            text: "Apakah Anda yakin ingin keluar?",
            icon: 'warning', // Ikon warning bisa dipertahankan atau diubah jika diinginkan
            showCancelButton: true,
            confirmButtonColor: '#0a84ff', // Biru untuk tombol konfirmasi (Ya)
            cancelButtonColor: '#6e7881',  // Abu-abu atau warna lain yang cocok untuk tombol batal (Tidak) di dark mode
            confirmButtonText: 'Ya, Logout!',
            cancelButtonText: 'Tidak, Batal',
            customClass: {
                popup: 'swal2-dark-popup', // Class untuk popup utama
                title: 'swal2-dark-title', // Class untuk judul
                htmlContainer: 'swal2-dark-html-container', // Class untuk teks konten
                confirmButton: 'swal2-dark-confirm-button', // Class untuk tombol konfirmasi
                cancelButton: 'swal2-dark-cancel-button', // Class untuk tombol batal
                icon: 'swal2-dark-icon' // Class untuk ikon (jika perlu styling khusus)
            },
            buttonsStyling: false // Penting agar customClass bisa menimpa style default sepenuhnya
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = logoutUrl;
            }
        });
    }
</script>

<style>
    /* Styling untuk SweetAlert2 Dark Theme */
    .swal2-dark-popup {
        background-color: #282c34 !important; /* Latar belakang popup gelap */
        color: #abb2bf !important; /* Warna teks umum terang */
        border-radius: 8px !important;
        border: 1px solid #4b5461; /* Border tipis (opsional) */
    }

    .swal2-dark-title {
        color: #0a84ff !important; /* Warna judul, bisa disesuaikan */
    }

    .swal2-dark-html-container {
        color: #abb2bf !important; /* Warna teks konten */
    }

    /* Styling untuk ikon warning di dark mode (jika defaultnya kurang terlihat) */
    .swal2-icon.swal2-warning.swal2-dark-icon {
        border-color: #f8bb86 !important; /* Warna border ikon warning */
        color: #f8bb86 !important; /* Warna ikon warning */
    }

    /* Styling umum untuk tombol agar konsisten */
    .swal2-dark-confirm-button,
    .swal2-dark-cancel-button {
        padding: 0.6em 1.2em !important;
        font-weight: 600 !important;
        border-radius: 5px !important;
        margin: 0 5px !important;
        transition: background-color 0.2s ease, border-color 0.2s ease;
    }

    /* Tombol Konfirmasi (Biru) */
    .swal2-dark-confirm-button {
        background-color: #0a84ff !important;
        color: white !important;
        border: 1px solid #0a84ff !important;
    }
    .swal2-dark-confirm-button:hover {
        background-color: #0073e6 !important; /* Sedikit lebih gelap saat hover */
        border-color: #0073e6 !important;
    }

    /* Tombol Batal (Abu-abu atau warna lain) */
    .swal2-dark-cancel-button {
        background-color: #6e7881 !important; /* Warna abu-abu */
        color: white !important;
        border: 1px solid #6e7881 !important;
    }
    .swal2-dark-cancel-button:hover {
        background-color: #5a6268 !important; /* Sedikit lebih gelap saat hover */
        border-color: #5a6268 !important;
    }
</style>

    <?= $this->renderSection('pageScripts') ?>
</body>
</html>