<?php namespace App\Controllers;

use App\Models\LayananModel; // Model untuk tabel layanan

class LayananController extends BaseController
{
    protected $layananModel;
    protected $session;

    public function __construct()
    {
        $this->layananModel = new LayananModel();
        $this->session = session(); // Untuk flashdata pesan
        helper(['form', 'url', 'number', 'date']); // Helper yang mungkin dibutuhkan

        // PENTING: Metode CRUD di controller ini diasumsikan akan dilindungi
        // oleh filter autentikasi admin yang akan diterapkan di Routes.
    }

    /**
     * [ADMIN] Menampilkan daftar semua layanan untuk manajemen (Read)
     */
    public function adminIndex() // Diganti dari index() agar jelas ini untuk admin
    {
        $data = [
            'title'   => 'Manajemen Daftar Layanan',
            'layanan' => $this->layananModel
                              ->orderBy('created_at', 'DESC')
                              ->findAll() 
        ];
        return view('admin/layanan/index_layanan', $data); // Path ke view daftar layanan admin
    }

    /**
     * [ADMIN] Menampilkan form untuk menambah layanan baru (Create - Form)
     */
    public function adminTambah()
    {
        $data = [
            'title' => 'Tambah Layanan Baru',
            'validation' => \Config\Services::validation()
        ];
        return view('admin/layanan/form_tambah_layanan', $data);
    }

    /**
     * [ADMIN] Memproses penyimpanan data layanan baru (Create - Process)
     */
    public function adminSimpan()
    {
        $rules = [
    'nama_layanan' => [
        'label' => 'Nama Layanan',
        'rules' => 'required|min_length[3]', // Contoh
        'errors' => [
            'required' => 'Nama layanan tidak boleh kosong, ya!', // PASTIKAN INI ADA
            'min_length' => 'Nama layanan terlalu pendek, minimal 3 karakter.' // PASTIKAN INI ADA
        ]
    ],
    'harga' => [
        'label' => 'Harga Layanan',
        'rules' => 'required|numeric|greater_than_equal_to[0]',
        'errors' => [
            'required' => 'Harga layanan mohon diisi.', // PASTIKAN INI ADA
            'numeric' => 'Harga harus angka saja.',
            'greater_than_equal_to' => 'Harga minimal 0 Rupiah.'
        ]
    ]
];

        if (!$this->validate($rules)) {
             $errors = $this->validator->getErrors();
            return redirect()->to('admin/layanan/tambah')->withInput(); // Menggunakan URL absolut atau named route
        }

        $dataToSave = [
            'nama_layanan'      => $this->request->getPost('nama_layanan'),
            'harga'             => $this->request->getPost('harga'),
            'is_delete_layanan' => '0'
        ];

        if ($this->layananModel->insert($dataToSave)) {
            $this->session->setFlashdata('success', 'Layanan baru berhasil ditambahkan!');
            return redirect()->to('admin/layanan');
        } else {
            $this->session->setFlashdata('error', 'Gagal menambahkan layanan.');
            return redirect()->to('admin/layanan/tambah')->withInput();
        }
    }

    /**
     * [ADMIN] Menampilkan form untuk mengedit layanan (Update - Form)
     * @param string $id ID Layanan
     */
    public function adminEdit($id)
    {
        $layanan = $this->layananModel->find($id);

        if (!$layanan) {
            $this->session->setFlashdata('error', 'Layanan tidak ditemukan.');
            return redirect()->to('admin/layanan');
        }

        $data = [
            'title'   => 'Edit Layanan: ' . esc($layanan['nama_layanan']),
            'layanan' => $layanan,
            'validation' => \Config\Services::validation()
        ];
        return view('admin/layanan/form_edit_layanan', $data);
    }

    /**
     * [ADMIN] Memproses pembaruan data layanan (Update - Process)
     * @param string $id ID Layanan
     */
    public function adminUpdate($id)
    {
        $layananSaatIni = $this->layananModel->find($id);
        if (!$layananSaatIni) {
            $this->session->setFlashdata('error', 'Layanan tidak ditemukan untuk diperbarui.');
            return redirect()->to('admin/layanan');
        }

        $namaLayananRule = 'required|min_length[3]|max_length[100]';
        if (strtolower($this->request->getPost('nama_layanan')) != strtolower($layananSaatIni['nama_layanan'])) {
            $namaLayananRule .= '|is_unique[layanan.nama_layanan]';
        }

        $rules = [
            'nama_layanan' => [
                'label' => 'Nama Layanan',
                'rules' => $namaLayananRule,
                'errors' => [ /* ... pesan error ... */ ]
            ],
            'harga' => [
                'label' => 'Harga Layanan',
                'rules' => 'required|numeric|greater_than[0]',
                'errors' => [ /* ... pesan error ... */ ]
            ]
        ];

        if (!$this->validate($rules)) {
            return redirect()->to('admin/layanan/edit/' . $id)->withInput();
        }

        $dataToUpdate = [
            'nama_layanan' => $this->request->getPost('nama_layanan'),
            'harga'        => $this->request->getPost('harga')
        ];

        if ($this->layananModel->update($id, $dataToUpdate)) {
            $this->session->setFlashdata('success', 'Data layanan berhasil diperbarui.');
            return redirect()->to('admin/layanan');
        } else {
            $this->session->setFlashdata('error', 'Gagal memperbarui data layanan.');
            return redirect()->to('admin/layanan/edit/' . $id)->withInput();
        }
    }

    /**
     * [ADMIN] Memproses penghapusan layanan (Soft Delete)
     * @param string $id ID Layanan
     */
    public function adminHapus($id)
{
    $methodDiterima = $this->request->getMethod();

    if (strtolower($methodDiterima) === 'post') {
        // Jika ini POST, kita lanjutkan ke logika hapus
        // Hapus semua dd() atau die() sebelumnya dari sini

        $layanan = $this->layananModel->find($id);
        if (!$layanan) {
            $this->session->setFlashdata('error', 'Layanan tidak ditemukan untuk dihapus (ID: '.esc($id).').');
            return redirect()->to(url_to('admin_layanan_index'));
        }

        if ($this->layananModel->update($id, ['is_delete_layanan' => '1'])) {
            $this->session->setFlashdata('success', 'Layanan "'.esc($layanan['nama_layanan']).'" berhasil dihapus (ditandai sebagai tidak aktif).');
        } else {
            $this->session->setFlashdata('error', 'Gagal menghapus layanan "'.esc($layanan['nama_layanan']).'". Kesalahan model: ' . json_encode($this->layananModel->errors()));
        }
        return redirect()->to(url_to('admin_layanan_index'));

    } else {
        // Jika BUKAN POST, tampilkan pesan ini dengan jelas
        $this->session->setFlashdata('error', 'AKSI DITOLAK: Metode request harus POST. Diterima: ' . strtoupper($methodDiterima));
        return redirect()->to(url_to('admin_layanan_index'));
    }
}
    public function indexPublik()
    {
        $data = [
            'title'            => 'Daftar Layanan Kami',
            'layanan_tersedia' => $this->layananModel
                                      ->where('is_delete_layanan', '0') // Hanya yang aktif
                                      ->orderBy('nama_layanan', 'ASC')
                                      ->findAll()
        ];
        return view('layanan/daftar_publik', $data); // Path ke view daftar layanan publik
    }
}