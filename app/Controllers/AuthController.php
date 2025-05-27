<?php

namespace App\Controllers;

use App\Models\PelangganModel;
use App\Models\AdminModel;

class AuthController extends BaseController
{
    protected $pelangganModel;
    protected $adminModel;
    protected $session;

    public function __construct()
    {
        $this->pelangganModel = new PelangganModel();
        $this->adminModel = new AdminModel();
        $this->session = session(); // Memuat library session
        helper(['form', 'url']); // Memuat helper untuk form dan URL
    }

    // --- Metode untuk Login Pelanggan ---
    public function tampilkanLoginPelanggan()
    {
        // Pastikan tidak ada admin yang sedang login di sesi yang sama jika diperlukan
        // if ($this->session->get('logged_in_admin')) {
        //     return redirect()->to('/admin/dashboard')->with('info', 'Anda sudah login sebagai Admin.');
        // }
        if ($this->session->get('logged_in_pelanggan')) {
            return redirect()->to('/')->with('info', 'Anda sudah login sebagai Pelanggan.'); // Arahkan ke dashboard pelanggan
        }
        return view('auth/login_pelanggan'); // VIEW KHUSUS PELANGGAN
    }

    public function prosesLoginPelanggan()
    {
        // ... (logika proses login pelanggan seperti yang sudah ada) ...
        // Validasi email dan password pelanggan
        // Cek ke tabel 'pelanggan'
        // Set session khusus pelanggan: 'id_pelanggan', 'nama_pelanggan', 'logged_in_pelanggan'
        // Redirect ke dashboard pelanggan atau halaman utama
        $rules = [
            'email_pelanggan' => 'required|valid_email',
            'password_pelanggan' => 'required'
        ];

        if (!$this->validate($rules)) {
            return view('auth/login_pelanggan', [ // Kembali ke view login pelanggan jika gagal
                'validation' => $this->validator
            ]);
        }

        $email = $this->request->getPost('email_pelanggan');
        $password = $this->request->getPost('password_pelanggan');

        $pelanggan = $this->pelangganModel->where('email_pelanggan', $email)->first();

        if ($pelanggan && password_verify($password, $pelanggan['password_pelanggan'])) {
            if ($pelanggan['is_delete_pelanggan'] == '1') {
                 return redirect()->back()->withInput()->with('error', 'Akun Anda telah dinonaktifkan.');
            }
            $this->session->set([
                'id_pelanggan' => $pelanggan['id_pelanggan'],
                'nama_pelanggan' => $pelanggan['nama_pelanggan'],
                'email_pelanggan' => $pelanggan['email_pelanggan'],
                'logged_in_pelanggan' => true
            ]);
            return redirect()->to('/booking/form')->with('success', 'Login berhasil!'); // Misal ke riwayat booking pelanggan
        } else {
            return redirect()->back()->withInput()->with('error', 'Email atau password salah.');
        }
    }

    public function logoutPelanggan()
    {
        $this->session->remove(['id_pelanggan', 'nama_pelanggan', 'email_pelanggan', 'logged_in_pelanggan']);
        return redirect()->to('/login-pelanggan')->with('success', 'Anda telah logout.');
    }


    // --- Metode untuk Login Admin ---
    public function tampilkanLoginAdmin()
    {
        // Pastikan tidak ada pelanggan yang sedang login di sesi yang sama jika diperlukan
        // if ($this->session->get('logged_in_pelanggan')) {
        //     return redirect()->to('/')->with('info', 'Anda sudah login sebagai Pelanggan.');
        // }
         if ($this->session->get('logged_in_admin')) {
            return redirect()->to('/admin/dashboard')->with('info', 'Anda sudah login sebagai Admin.');
        }
        return view('auth/login_admin'); // VIEW KHUSUS ADMIN
    }

    public function prosesLoginAdmin()
    {
        // ... (logika proses login admin seperti yang sudah ada) ...
        // Validasi username dan password admin
        // Cek ke tabel 'admin'
        // Set session khusus admin: 'id_admin', 'nama_admin', 'akses_level', 'logged_in_admin'
        // Redirect ke dashboard admin
        $rules = [
            'username_admin' => 'required',
            'password_admin' => 'required'
        ];

        if (!$this->validate($rules)) {
            return view('auth/login_admin', [ // Kembali ke view login admin jika gagal
                'validation' => $this->validator
            ]);
        }

        $username = $this->request->getPost('username_admin');
        $password = $this->request->getPost('password_admin');

        $admin = $this->adminModel->where('username_admin', $username)->first();

        if ($admin && password_verify($password, $admin['password_admin'])) {
             if ($admin['is_delete_admin'] == '1') {
                 return redirect()->back()->withInput()->with('error', 'Akun Anda telah dinonaktifkan.');
            }
            $this->session->set([
                'id_admin' => $admin['id_admin'],
                'nama_admin' => $admin['nama_admin'],
                'username_admin' => $admin['username_admin'],
                'akses_level' => $admin['akses_level'],
                'logged_in_admin' => true
            ]);
            return redirect()->to('/')->with('success', 'Login berhasil!');
        } else {
            return redirect()->back()->withInput()->with('error', 'Username atau password salah.');
        }
    }

    public function logoutAdmin()
    {
        $this->session->remove(['id_admin', 'nama_admin', 'username_admin', 'akses_level', 'logged_in_admin']);
        return redirect()->to('/admin/login')->with('success', 'Anda telah logout.');
    }

    // Metode registrasi pelanggan bisa tetap di sini atau dipindah ke PelangganController jika dirasa lebih sesuai
    public function tampilkanRegistrasiPelanggan()
    {
        return view('auth/registrasi_pelanggan');
    }

    public function prosesRegistrasiPelanggan()
{
    $rules = [
        'nama_pelanggan' => 'required|min_length[3]|max_length[100]',
        'no_hp' => 'required|min_length[10]|max_length[15]',
        'email_pelanggan' => 'required|valid_email|is_unique[pelanggan.email_pelanggan]',
        'password_pelanggan' => 'required|min_length[5]',
        'konfirmasi_password' => 'required|matches[password_pelanggan]'
    ];

    if (!$this->validate($rules)) {
        return redirect()->back()->withInput()->with('validation', $this->validator);
    }

    $data = [
        'nama_pelanggan' => $this->request->getPost('nama_pelanggan'),
        'no_hp' => $this->request->getPost('no_hp'),
        'email_pelanggan' => $this->request->getPost('email_pelanggan'),
        'password_pelanggan' => $this->request->getPost('password_pelanggan'),
        'is_delete_pelanggan' => '0'
    ];

    if ($this->pelangganModel->insert($data)) {
        return redirect()->to('/login-pelanggan')->with('success', 'Registrasi berhasil! Silakan login.');
    } else {
        return redirect()->back()->withInput()->with('error', 'Registrasi gagal, coba lagi.');
    }
}
}