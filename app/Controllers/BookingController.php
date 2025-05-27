<?php

namespace App\Controllers;

use App\Models\LayananModel;
use App\Models\BookingModel;
use App\Models\DetailBookingModel; 

class BookingController extends BaseController
{
    protected $layananModel;
    protected $bookingModel;
    protected $detailBookingModel; 
    protected $session;

    public function __construct()
    {
        $this->layananModel = new LayananModel();
        $this->bookingModel = new BookingModel();       // <-- Inisialisasi di sini
        $this->detailBookingModel = new DetailBookingModel(); // <-- Inisialisasi di sini
        $this->session = session();
        helper(['form', 'url', 'number', 'date']);
    }

    public function tampilkanFormBooking()
    {
        if (!$this->session->get('logged_in_pelanggan') && !$this->session->get('logged_in_admin')) {
            $this->session->setFlashdata('error', 'Anda harus login terlebih dahulu untuk membuat booking.');
            return redirect()->to(url_to('login_pelanggan_show'));
        }

        $layananAktif = $this->layananModel
            ->where('is_delete_layanan', '0')
            ->orderBy('nama_layanan', 'ASC')
            ->findAll();

        // Ambil daftar semua pelanggan jika admin yang login
        $pelangganModel = new \App\Models\PelangganModel(); // Pastikan model pelanggan Anda ada
        $daftarPelanggan = [];
        if ($this->session->get('logged_in_admin')) {
            $daftarPelanggan = $pelangganModel->findAll();
        }

        $data = [
            'title'            => 'Formulir Booking Layanan',
            'layanan_tersedia' => $layananAktif,
            'validation'       => \Config\Services::validation(),
            'daftar_pelanggan' => $daftarPelanggan // Kirim daftar pelanggan ke view
        ];
        return view('booking/form_booking', $data);
    }

    public function prosesBooking()
    {
        if (!$this->session->get('logged_in_pelanggan') && !$this->session->get('logged_in_admin')) {
            $this->session->setFlashdata('error', 'Sesi Anda berakhir atau Anda belum login. Silakan login kembali.');
            return redirect()->to(url_to('login_pelanggan_show'));
        }

        $rules = [
            'id_pelanggan_booking' => 'required',
            'tanggal_booking'    => 'required|valid_date[Y-m-d]',
            'layanan_ids'        => 'required|is_array',
            'layanan_ids.*'      => 'string'
        ];

        $messages = [
            'id_pelanggan_booking' => ['required' => 'Informasi pelanggan tidak ditemukan atau tidak valid.'],
            'tanggal_booking' => [
                'required' => 'Tanggal booking wajib diisi.',
                'valid_date' => 'Format tanggal booking tidak valid. Gunakan formatelolaan-MM-DD.'
            ],
            'layanan_ids' => [
                'required' => 'Anda harus memilih minimal satu layanan.',
                'is_array' => 'Pilihan layanan tidak valid.'
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->to(url_to('booking_form_show'))->withInput()->with('validation_errors', $this->validator);
        }

        $tanggalBookingInput = $this->request->getPost('tanggal_booking');
        $tanggalBookingDB = $tanggalBookingInput . ' 00:00:00';

        $layananIds = $this->request->getPost('layanan_ids');

        if (empty($layananIds)) {
            return redirect()->to(url_to('booking_form_show'))->withInput()->with('validation_errors', $this->validator->setError('layanan_ids', 'Anda harus memilih minimal satu layanan.'));
        }

        $layananTerpilihData = $this->layananModel->whereIn('id_layanan', $layananIds)
                                               ->where('is_delete_layanan', '0')
                                               ->findAll();

        if (count($layananTerpilihData) !== count($layananIds)) {
            $this->session->setFlashdata('error', 'Beberapa layanan yang dipilih tidak valid atau sudah tidak tersedia. Mohon periksa kembali pilihan Anda.');
            return redirect()->to(url_to('booking_form_show'))->withInput();
        }

        $totalHarga = 0;
        foreach ($layananTerpilihData as $lyn) {
            $totalHarga += (float) $lyn['harga'];
        }

        $bookingModel = new BookingModel();
        $detailBookingModel = new DetailBookingModel();

        $this->db = \Config\Database::connect();
        $this->db->transStart();

        $idPelanggan = '';
        // Prioritaskan ID pelanggan dari sesi jika ada
        if (session()->get('logged_in_pelanggan')) {
            $idPelanggan = $this->session->get('id_pelanggan');
        } elseif (session()->get('logged_in_admin')) {
            // Jika admin login, coba ambil ID pelanggan dari input hidden
            $idPelanggan = $this->request->getPost('id_pelanggan_booking');
             // Tambahan validasi, pastikan input tidak kosong (opsional, tergantung kebutuhan)
            if (empty($idPelanggan) || $idPelanggan === 'ADM_BOOKING') {
                $this->db->transRollback();
                $this->session->setFlashdata('error', 'Informasi pelanggan tidak valid.');
                return redirect()->to(url_to('booking_form_show'))->withInput();
            }
        } else {
            $this->db->transRollback();
            $this->session->setFlashdata('error', 'Sesi tidak valid.');
            return redirect()->to(url_to('home'));
        }

        $dataBooking = [
            'id_pelanggan'    => $idPelanggan,
            'tanggal_booking' => $tanggalBookingDB,
            'total_harga'     => $totalHarga
        ];

        $idBookingBaru = $bookingModel->insert($dataBooking, true);

        if (!$idBookingBaru) {
            $this->db->transRollback();
            log_message('error', 'Gagal menyimpan data booking utama. Errors: ' . json_encode($bookingModel->errors()));
            $this->session->setFlashdata('error', 'Gagal menyimpan data booking. Silakan coba beberapa saat lagi.');
            return redirect()->to(url_to('booking_form_show'))->withInput();
        }

        $semuaDetailBerhasil = true;
        foreach ($layananIds as $idLayanan) {
            $dataDetail = [
                'id_booking' => $idBookingBaru,
                'id_layanan' => $idLayanan
            ];
            if (!$detailBookingModel->insert($dataDetail)) {
                $semuaDetailBerhasil = false;
                log_message('error', 'Gagal menyimpan item detail booking. Data: ' . json_encode($dataDetail) . '. Errors: ' . json_encode($detailBookingModel->errors()));
                break;
            }
        }

        if ($semuaDetailBerhasil) {
            $this->db->transComplete();
            $this->session->setFlashdata('success', 'Booking Anda dengan ID #' . esc($idBookingBaru) . ' berhasil dibuat!');
            return redirect()->to(url_to('booking_success_page', $idBookingBaru));
        } else {
            $this->db->transRollback();
            $this->session->setFlashdata('error', 'Gagal menyimpan semua detail layanan untuk booking. Mohon coba lagi.');
            return redirect()->to(url_to('booking_form_show'))->withInput();
        }
    }
    /**
     * Menampilkan halaman konfirmasi setelah booking sukses.
     * (Anda perlu membuat view untuk ini)
     */
    public function bookingSukses($idBooking)
    {
        // Ambil data booking untuk ditampilkan
        $bookingModel = new BookingModel();
        $booking = $bookingModel->find($idBooking);

        if (!$booking) {
            $this->session->setFlashdata('error', 'Data booking tidak ditemukan.');
            return redirect()->to(url_to('home')); // atau ke halaman lain
        }

        // Pastikan booking ini milik user yang login (jika pelanggan) atau boleh dilihat admin
        $userCanView = false;
        if (session()->get('logged_in_pelanggan') && session()->get('id_pelanggan') == $booking['id_pelanggan']) {
            $userCanView = true;
        } elseif (session()->get('logged_in_admin')) {
            $userCanView = true; // Admin boleh lihat semua
        }

        if (!$userCanView) {
             $this->session->setFlashdata('error', 'Anda tidak memiliki akses untuk melihat detail booking ini.');
            return redirect()->to(url_to('home'));
        }


        // Ambil detail layanan yang di-booking
        $detailBookingModel = new DetailBookingModel();
        $detailLayanan = $detailBookingModel
            ->select('layanan.nama_layanan, layanan.harga')
            ->join('layanan', 'layanan.id_layanan = detail_booking.id_layanan')
            ->where('detail_booking.id_booking', $idBooking)
            ->findAll();

        $data = [
            'title' => 'Booking Berhasil!',
            'booking' => $booking,
            'detail_layanan' => $detailLayanan
        ];
        return view('booking/sukses_booking', $data); // Buat view ini: app/Views/booking/sukses_booking.php
    }

    public function daftarBookingPelanggan()
    {
        // Pastikan pelanggan sudah login
        if (!$this->session->get('logged_in_pelanggan')) {
            $this->session->setFlashdata('error', 'Anda harus login untuk melihat riwayat booking.');
            return redirect()->to(url_to('login_pelanggan_show'));
        }

        $idPelanggan = $this->session->get('id_pelanggan');

        // Ambil data booking utama milik pelanggan, urutkan dari yang terbaru
        $bookingsData = $this->bookingModel
            ->where('id_pelanggan', $idPelanggan)
            ->orderBy('tanggal_booking', 'DESC')
            ->findAll();

        $dataBookings = [];
        if (!empty($bookingsData)) {
            foreach ($bookingsData as $booking) {
                // Untuk setiap booking, ambil detail layanan yang dipesan
                $detailLayanan = $this->detailBookingModel
                    ->select('layanan.nama_layanan, layanan.harga') // Ambil nama dan harga layanan
                    ->join('layanan', 'layanan.id_layanan = detail_booking.id_layanan', 'left')
                    ->where('detail_booking.id_booking', $booking['id_booking'])
                    ->findAll();
                
                $booking['detail_layanan_items'] = $detailLayanan; // Tambahkan detail layanan ke array booking
                $dataBookings[] = $booking; // Masukkan ke array hasil
            }
        }

        $data = [
            'title'   => 'Riwayat Booking Saya',
            'bookings' => $dataBookings
        ];

        return view('booking/riwayat_booking', $data);
    }

    public function hapusBookingPelanggan($idBooking)
    {
        // Pastikan pelanggan sudah login
        if (!$this->session->get('logged_in_pelanggan')) {
            $this->session->setFlashdata('error_booking_delete', 'Anda harus login untuk melakukan aksi ini.');
            return redirect()->to(url_to('login_pelanggan_show'));
        }

        // Pastikan request adalah POST
        if (strtolower($this->request->getMethod()) !== 'post') {
            $this->session->setFlashdata('error_booking_action', 'Metode request tidak valid untuk aksi ini. Diterima: ' . strtoupper($this->request->getMethod()));
            return redirect()->to(url_to('pelanggan_booking_history'));
        }

        $idPelangganSession = $this->session->get('id_pelanggan');
        $booking = $this->bookingModel->find($idBooking);

        // Cek apakah booking ada dan milik pelanggan yang sedang login
        if (!$booking) {
            $this->session->setFlashdata('error_booking_delete', 'Booking tidak ditemukan.');
            return redirect()->to(url_to('pelanggan_booking_history'));
        }

        if ($booking['id_pelanggan'] !== $idPelangganSession) {
            $this->session->setFlashdata('error_booking_delete', 'Anda tidak berhak menghapus booking ini.');
            return redirect()->to(url_to('pelanggan_booking_history'));
        }


        // Mulai transaksi database
        $db = \Config\Database::connect();
        $db->transStart();

        // 1. Hapus dari detail_booking terlebih dahulu
        $this->detailBookingModel->where('id_booking', $idBooking)->delete();

        // 2. Hapus dari booking
        $this->bookingModel->delete($idBooking);

        if ($db->transStatus() === false) {
            $db->transRollback();
            log_message('error', 'Gagal menghapus booking ID: ' . $idBooking . ' untuk pelanggan ID: ' . $idPelangganSession);
            $this->session->setFlashdata('error_booking_delete', 'Gagal membatalkan booking. Terjadi kesalahan database.');
        } else {
            $db->transCommit();
            $this->session->setFlashdata('success_booking_delete', 'Booking dengan ID #'.esc($idBooking).' berhasil dibatalkan/dihapus.');
        }

        return redirect()->to(url_to('pelanggan_booking_history'));
    }
}