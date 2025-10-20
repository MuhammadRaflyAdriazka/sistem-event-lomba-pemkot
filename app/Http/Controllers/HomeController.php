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
        $sekarang = Carbon::now();

        // Ambil acara yang:
        // 1. Statusnya 'active'
        // 2. Tanggal akhir pendaftarannya belum lewat
        $acara = Acara::where('status', 'active')
                      ->where('tanggal_akhir_daftar', '>=', $sekarang)
                      ->orderBy('tanggal_mulai_daftar', 'asc') // Urutkan berdasarkan yang paling cepat buka
                      ->get();

        // Tampilkan view 'welcome' dan kirimkan data acara ke dalamnya
        // dengan nama variabel 'semuaAcara'
        return view('welcome', ['semuaAcara' => $acara]);
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