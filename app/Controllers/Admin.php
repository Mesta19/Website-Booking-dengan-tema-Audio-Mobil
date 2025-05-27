<?php

namespace App\Controllers;

use App\Models\BookingModel;
use App\Models\PelangganModel;
use App\Models\DetailBookingModel;
use App\Models\LayananModel;

class Admin extends BaseController
{
    protected $bookingModel;
    protected $pelangganModel;
    protected $detailBookingModel;
    protected $layananModel;

    public function __construct()
    {
        $this->bookingModel = new BookingModel();
        $this->pelangganModel = new PelangganModel();
        $this->detailBookingModel = new DetailBookingModel();
        $this->layananModel = new LayananModel();
    }

    public function dashboard()
    {
        $data['title'] = 'Dashboard Admin';
        $data['bookings'] = $this->bookingModel
            ->orderBy('tanggal_booking', 'DESC')
            ->findAll();

        // Ambil informasi pelanggan untuk setiap booking
        foreach ($data['bookings'] as &$booking) {
            $booking['pelanggan'] = $this->pelangganModel->find($booking['id_pelanggan']);
            // Ambil detail layanan untuk setiap booking
            $booking['detail_layanan'] = $this->detailBookingModel
                ->select('layanan.nama_layanan, layanan.harga')
                ->join('layanan', 'layanan.id_layanan = detail_booking.id_layanan')
                ->where('detail_booking.id_booking', $booking['id_booking'])
                ->findAll();
        }

        return view('admin/dashboard', $data);
    }

   public function hapusBooking(string $id)
{
    // Hapus detail booking terlebih dahulu
    $this->detailBookingModel->where('id_booking', $id)->delete();

    // Baru hapus booking
    if ($this->bookingModel->delete($id)) {
        return redirect()->to('/admin/dashboard')->with('success', 'Booking berhasil dihapus.');
    } else {
        return redirect()->to('/admin/dashboard')->with('error', 'Gagal menghapus booking.');
    }
}
}