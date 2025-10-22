<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Acara;
use App\Models\Pendaftaran;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PanitiaController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Pastikan user adalah panitia
        if ($user->peran !== 'panitia') { // ✅ Fix: pakai 'peran' bukan 'role'
            return redirect()->route('dashboard')->with('error', 'Akses ditolak');
        }

        // Cek apakah user ini terdaftar sebagai panitia untuk acara tertentu
        $panitiaAcara = DB::table('panitia_acara')
            ->where('id_pengguna', $user->id) // ✅ Fix: pakai id_pengguna
            ->first();

        if (!$panitiaAcara) {
            return redirect()->route('dashboard')->with('error', 'Anda belum ditugaskan ke acara manapun');
        }

        // Ambil data acara yang ditugaskan
        $acara = Acara::findOrFail($panitiaAcara->id_acara);

        // Hitung statistik pendaftaran untuk acara ini - hanya total pendaftar
        $totalPendaftar = Pendaftaran::where('id_acara', $acara->id)->count();

        return view('panitia.dashboard', compact('acara', 'totalPendaftar'));
    }
}
