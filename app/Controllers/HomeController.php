<?php

namespace App\Controllers;

class HomeController extends BaseController
{
    public function index()
    {
        $bookingModel = new \App\Models\BookingModel();
        $bookingModel->hapusBookingKadaluarsa();
        $data = [
            'title' => 'Selamat Datang di Global Service Audio' // Judul untuk halaman beranda
        ];
        return view('beranda', $data); // Memuat view 'beranda.php'
    }
}
