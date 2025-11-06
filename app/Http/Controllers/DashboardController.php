<?php

namespace App\Http\Controllers;

use App\Models\Acara;
use App\Models\Pendaftaran;
use App\Models\Pengumuman;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Menampilkan dashboard peserta dengan acara yang tersedia dan status pendaftaran
     */
    public function index()
    {
        $user = auth()->user();
        $sekarang = Carbon::now();

        // Ambil acara yang masih aktif dan pendaftarannya masih buka
        $acaraTersedia = Acara::where('status', 'active')
                             ->where('tanggal_akhir_daftar', '>=', $sekarang)
                             ->orderBy('tanggal_mulai_daftar', 'asc')
                             ->get();

        // Ambil pendaftaran user yang sedang login
        $pendaftaranUser = Pendaftaran::where('id_pengguna', $user->id)
                                     ->with('acara')
                                     ->get();

        return view('dashboard', compact('acaraTersedia', 'pendaftaranUser'));
    }

    /**
     * Menampilkan halaman "Acara Saya" - daftar acara yang sudah didaftari user
     */
    public function acaraSaya()
    {
        $user = auth()->user();

        // Ambil semua pendaftaran user dengan relasi acara
        $pendaftaranUser = Pendaftaran::where('id_pengguna', $user->id)
                                     ->with('acara')
                                     ->orderBy('created_at', 'desc')
                                     ->get();

        return view('acara', compact('pendaftaranUser'));
    }

    /**
     * Menampilkan detail status pendaftaran user untuk acara tertentu
     */
    public function detailPendaftaran(Pendaftaran $pendaftaran)
    {
        $user = auth()->user();

        // Pastikan pendaftaran ini milik user yang sedang login
        if ($pendaftaran->id_pengguna !== $user->id) {
            abort(403, 'Anda tidak memiliki akses ke pendaftaran ini.');
        }

        // Ambil data acara
        $acara = $pendaftaran->acara;

        return view('detail-pendaftaran', compact('pendaftaran', 'acara'));
    }

    /**
     * Menampilkan pengumuman untuk peserta yang diterima
     */
    public function pengumuman(Pendaftaran $pendaftaran)
    {
        $user = auth()->user();

        // Pastikan pendaftaran ini milik user yang sedang login
        if ($pendaftaran->id_pengguna !== $user->id) {
            abort(403, 'Anda tidak memiliki akses ke pendaftaran ini.');
        }

        // Hanya peserta yang diterima yang bisa melihat pengumuman
        if ($pendaftaran->status !== 'diterima' && $pendaftaran->status !== 'disetujui') {
            return redirect()->route('acara')->with('error', 'Hanya peserta yang diterima yang dapat melihat pengumuman.');
        }

        // Ambil semua pengumuman untuk acara ini
        $pengumumanList = Pengumuman::where('id_acara', $pendaftaran->acara->id)
                                  ->orderBy('created_at', 'desc')
                                  ->get();

        return view('pengumuman', compact('pendaftaran', 'pengumumanList'));
    }
}
