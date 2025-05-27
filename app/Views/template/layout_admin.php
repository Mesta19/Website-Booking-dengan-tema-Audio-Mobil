<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><?= $this->renderSection('title', 'Admin Panel') ?></title>

    <link rel="stylesheet" href="<?= base_url('css/style.css') ?>">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <?= $this->renderSection('pageStyles') ?>
</head>
<body>

   <header>
        <div class="logo">GSAM Admin Panel</div>
        <nav>
            <a href="<?= base_url('/') ?>">Kembali ke Beranda</a>
        </nav>
    </header>

    <main class="main-content">
        <?= $this->renderSection('content') ?>
    </main>

    <footer>
        <p>Hubungi kami: <a href="mailto:globalservice4545@gmail.com">globalservice4545@gmail.com</a> | <a href="https://wa.me/6287881620835">Whats'App: +62 878 8162 0835</a></p>
        <p>Beli Produk Kami di
            <a href="https://shopee.co.id/carstereoaudio" target="_blank" rel="noopener">Shopee</a> |
            <a href="https://www.tokopedia.com/caraudiostereo/product" target="_blank" rel="noopener">Tokopedia</a> |
        </p>
        <p>&copy; <?= date('Y') ?> Global Service Audio. Hak Cipta Dilindungi.</p>
    </footer>

    <script>
        function confirmLogout(event, userType) {
            event.preventDefault();
            let logoutUrl = event.currentTarget.href;

            Swal.fire({
                title: 'Konfirmasi Logout',
                text: "Apakah Anda yakin ingin keluar?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#0a84ff',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Logout!',
                cancelButtonText: 'Tidak, Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = logoutUrl;
                }
            });
        }
    </script>

    <?= $this->renderSection('pageScripts') ?>
</body>
</html>