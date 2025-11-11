<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Acara;
use App\Models\Pendaftaran;
use App\Models\User;
use App\Models\Dinas;
use App\Models\KolomFormulirAcara;
use Illuminate\Support\Facades\Auth;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Pastikan user adalah admin dan memiliki id_dinas
        if (!$user || !$user->id_dinas) {
            return redirect()->route('dashboard')->with('error', 'Akses ditolak');
        }

        // Total Event dari dinas admin (semua status)
        $totalEvent = Acara::where('id_dinas', $user->id_dinas)->count();
        
        // Peserta Aktif - total pendaftar dari acara yang masih aktif
        $pesertaAktif = Pendaftaran::whereHas('acara', function($query) use ($user) {
            $query->where('id_dinas', $user->id_dinas)
                  ->where('status', 'active');
        })->count();
        
        // Event Selesai - acara dengan status inactive (ditandai selesai oleh admin)
        $eventSelesai = Acara::where('id_dinas', $user->id_dinas)
            ->where('status', 'inactive')
            ->count();

        return view('admin.dashboard', compact('totalEvent', 'pesertaAktif', 'eventSelesai'));
    }

    public function kelola()
    {
        $user = Auth::user();
        
        // Pastikan user adalah admin dan memiliki id_dinas
        if (!$user || !$user->id_dinas) {
            return redirect()->route('dashboard')->with('error', 'Akses ditolak');
        }

        // Ambil semua event aktif dari dinas admin dengan relasi pendaftaran
        $events = Acara::where('id_dinas', $user->id_dinas)
            ->where('status', 'active')
            ->with('pendaftaran') // Load relasi pendaftaran untuk statistik
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.kelola', compact('events'));
    }

    public function acaraSelesai()
    {
        $user = Auth::user();
        
        // Pastikan user adalah admin dan memiliki id_dinas
        if (!$user || !$user->id_dinas) {
            return redirect()->route('dashboard')->with('error', 'Akses ditolak');
        }

        // Ambil semua event yang sudah selesai (status inactive) dari dinas admin
        $events = Acara::where('id_dinas', $user->id_dinas)
            ->where('status', 'inactive')
            ->with('pendaftaran') // Load relasi pendaftaran untuk statistik
            ->orderBy('updated_at', 'desc') // Urutkan berdasarkan yang terakhir diselesaikan
            ->get();

        return view('admin.acara-selesai', compact('events'));
    }
}