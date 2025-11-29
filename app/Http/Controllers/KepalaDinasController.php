<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Acara;
use App\Models\Pendaftaran;
use Illuminate\Support\Facades\Auth;

class KepalaDinasController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        
        // Pastikan user adalah kepala dinas dan memiliki id_dinas
        if (!$user || !$user->id_dinas) {
            return redirect()->route('dashboard')->with('error', 'Akses ditolak');
        }

        // Total Acara dari dinas kepala (semua status)
        $totalAcara = Acara::where('id_dinas', $user->id_dinas)->count();

        // Peserta Aktif - hitung dari semua pendaftaran yang statusnya 'pending' atau 'disetujui' 
        // dari acara yang masih aktif di dinas kepala
        $pesertaAktif = Pendaftaran::whereHas('acara', function($query) use ($user) {
            $query->where('id_dinas', $user->id_dinas)
                  ->where('status', 'active');
        })->whereIn('status', ['pending', 'disetujui'])->count();

        // Acara Selesai - acara dengan status inactive (ditandai selesai oleh admin)
        $eventSelesai = Acara::where('id_dinas', $user->id_dinas)
            ->where('status', 'inactive')
            ->count();

        // Data untuk donut chart - Status Pendaftaran
        $pendaftarMenunggu = Pendaftaran::whereHas('acara', function($query) use ($user) {
            $query->where('id_dinas', $user->id_dinas);
        })->where('status', 'pending')->count();

        $pendaftarDiterima = Pendaftaran::whereHas('acara', function($query) use ($user) {
            $query->where('id_dinas', $user->id_dinas);
        })->where('status', 'disetujui')->count();

        $pendaftarDitolak = Pendaftaran::whereHas('acara', function($query) use ($user) {
            $query->where('id_dinas', $user->id_dinas);
        })->where('status', 'ditolak')->count();

        return view('kepala.dashboard', compact(
            'totalAcara', 
            'pesertaAktif', 
            'eventSelesai',
            'pendaftarMenunggu',
            'pendaftarDiterima', 
            'pendaftarDitolak'
        ));
    }

    public function editProfile()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        // Kepala Dinas hanya bisa update password melalui form terpisah
        // Nama dan email tidak bisa diubah untuk kepala dinas
        
        return redirect()->route('kepala.profile.edit')->with('success', 'Profil berhasil diperbarui!');
    }
}