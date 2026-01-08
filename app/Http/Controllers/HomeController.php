<?php

namespace App\Http\Controllers;

use App\Models\Acara; // Import Model Acara
use Illuminate\Http\Request;
use Carbon\Carbon; // Kita perlu ini untuk mengecek tanggal

class HomeController extends Controller
{
    /**
     * Menampilkan halaman utama (welcome page) dengan daftar acara.
     */
    public function index()
    {
        // Ambil semua acara yang statusnya 'active'
        // Acara tetap muncul walaupun sudah lewat tenggat pendaftaran
        // Untuk menghilangkan acara, admin/dinas harus ubah status jadi 'selesai'
        $acara = Acara::where('status', 'active')
                      ->orderBy('tanggal_mulai_daftar', 'desc')
                      ->get();

        // Dapatkan tanggal sekarang untuk pengecekan di view
        $sekarang = Carbon::now()->format('Y-m-d');

        // Tampilkan view 'welcome' dan kirimkan data acara ke dalamnya
        // dengan nama variabel 'semuaAcara'
        return view('welcome', [
            'semuaAcara' => $acara,
            'sekarang' => $sekarang
        ]);
    }

    /**
     * Menampilkan detail acara untuk halaman publik.
     */
    public function show(Acara $acara)
    {
        // Pastikan acara masih aktif
        if ($acara->status !== 'active') {
            abort(404);
        }

        return view('detail', compact('acara'));
    }
}