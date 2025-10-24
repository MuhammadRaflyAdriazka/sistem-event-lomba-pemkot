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

        return view('panitia.dashboard', compact('acara'));
    }

    public function peserta()
    {
        $user = Auth::user();
        
        // Pastikan user adalah panitia
        if ($user->peran !== 'panitia') {
            return redirect()->route('dashboard')->with('error', 'Akses ditolak');
        }

        // Cek apakah user ini terdaftar sebagai panitia untuk acara tertentu
        $panitiaAcara = DB::table('panitia_acara')
            ->where('id_pengguna', $user->id)
            ->first();

        if (!$panitiaAcara) {
            return redirect()->route('dashboard')->with('error', 'Anda belum ditugaskan ke acara manapun');
        }

        // Ambil data acara yang ditugaskan
        $acara = Acara::findOrFail($panitiaAcara->id_acara);

        // Ambil data peserta yang mendaftar untuk acara ini (hanya yang pending untuk sistem seleksi)
        $peserta = Pendaftaran::with(['pengguna', 'dataPendaftaran'])
            ->where('id_acara', $acara->id)
            ->where('status', 'pending') // Hanya tampilkan yang menunggu seleksi
            ->get();

        // Hitung statistik
        $totalPendaftar = Pendaftaran::where('id_acara', $acara->id)->count();
        $jumlahDiterima = Pendaftaran::where('id_acara', $acara->id)->where('status', 'disetujui')->count();
        $jumlahDitolak = Pendaftaran::where('id_acara', $acara->id)->where('status', 'ditolak')->count();

        return view('panitia.peserta-seleksi', compact('acara', 'peserta', 'totalPendaftar', 'jumlahDiterima', 'jumlahDitolak'));
    }

    public function detailPeserta($pendaftaranId)
    {
        $user = Auth::user();
        
        // Pastikan user adalah panitia
        if ($user->peran !== 'panitia') {
            return redirect()->route('dashboard')->with('error', 'Akses ditolak');
        }

        // Cek apakah user ini terdaftar sebagai panitia
        $panitiaAcara = DB::table('panitia_acara')
            ->where('id_pengguna', $user->id)
            ->first();

        if (!$panitiaAcara) {
            return redirect()->route('dashboard')->with('error', 'Anda belum ditugaskan ke acara manapun');
        }

        // Ambil data pendaftaran dengan validasi bahwa pendaftaran ini untuk acara yang ditugaskan ke panitia
        $pendaftaran = Pendaftaran::with(['pengguna', 'dataPendaftaran', 'acara'])
            ->where('id', $pendaftaranId)
            ->where('id_acara', $panitiaAcara->id_acara)
            ->firstOrFail();

        // Ambil kolom formulir untuk acara ini
        $kolomFormulir = DB::table('kolom_formulir_acara')
            ->where('id_acara', $pendaftaran->id_acara)
            ->orderBy('urutan_kolom')
            ->get();

        return view('panitia.detail-seleksi', compact('pendaftaran', 'kolomFormulir'));
    }

    public function showFile($filename)
    {
        $user = Auth::user();
        
        // Pastikan user adalah panitia
        if ($user->peran !== 'panitia') {
            abort(403);
        }

        // Cari file di berbagai lokasi
        $possiblePaths = [
            storage_path('app/private/public/pendaftaran/' . $filename),
            storage_path('app/public/' . $filename),
            storage_path('app/' . $filename)
        ];

        foreach ($possiblePaths as $path) {
            if (file_exists($path)) {
                $mimeType = mime_content_type($path);
                return response()->file($path, [
                    'Content-Type' => $mimeType,
                ]);
            }
        }

        abort(404, 'File tidak ditemukan');
    }

    public function terimaPeserta($pendaftaranId)
    {
        $user = Auth::user();
        
        // Pastikan user adalah panitia
        if ($user->peran !== 'panitia') {
            return redirect()->route('dashboard')->with('error', 'Akses ditolak');
        }

        // Cek apakah user ini terdaftar sebagai panitia
        $panitiaAcara = DB::table('panitia_acara')
            ->where('id_pengguna', $user->id)
            ->first();

        if (!$panitiaAcara) {
            return redirect()->route('dashboard')->with('error', 'Anda belum ditugaskan ke acara manapun');
        }

        // Ambil data pendaftaran dengan validasi
        $pendaftaran = Pendaftaran::where('id', $pendaftaranId)
            ->where('id_acara', $panitiaAcara->id_acara)
            ->firstOrFail();

        // Cek apakah masih ada kuota
        $acara = Acara::findOrFail($panitiaAcara->id_acara);
        $jumlahDiterima = Pendaftaran::where('id_acara', $acara->id)
            ->where('status', 'disetujui')
            ->count();

        if ($jumlahDiterima >= $acara->kuota) {
            return redirect()->back()->with('error', 'Kuota sudah penuh! Tidak bisa menerima peserta lagi.');
        }

        // Update status menjadi diterima
        $pendaftaran->update([
            'status' => 'disetujui',
            'alasan_penolakan' => null
        ]);

        return redirect()->route('panitia.peserta')->with('success', 'Peserta berhasil diterima!');
    }

    public function tolakPeserta($pendaftaranId)
    {
        $user = Auth::user();
        
        // Pastikan user adalah panitia
        if ($user->peran !== 'panitia') {
            return redirect()->route('dashboard')->with('error', 'Akses ditolak');
        }

        // Cek apakah user ini terdaftar sebagai panitia
        $panitiaAcara = DB::table('panitia_acara')
            ->where('id_pengguna', $user->id)
            ->first();

        if (!$panitiaAcara) {
            return redirect()->route('dashboard')->with('error', 'Anda belum ditugaskan ke acara manapun');
        }

        // Ambil data pendaftaran dengan validasi
        $pendaftaran = Pendaftaran::where('id', $pendaftaranId)
            ->where('id_acara', $panitiaAcara->id_acara)
            ->firstOrFail();

        // Update status menjadi ditolak
        $pendaftaran->update([
            'status' => 'ditolak',
            'alasan_penolakan' => 'Tidak memenuhi kriteria seleksi'
        ]);

        return redirect()->route('panitia.peserta')->with('success', 'Peserta berhasil ditolak!');
    }

    public function pesertaDiterima()
    {
        $user = Auth::user();
        
        // Pastikan user adalah panitia
        if ($user->peran !== 'panitia') {
            return redirect()->route('dashboard')->with('error', 'Akses ditolak');
        }

        // Cek apakah user ini terdaftar sebagai panitia untuk acara tertentu
        $panitiaAcara = DB::table('panitia_acara')
            ->where('id_pengguna', $user->id)
            ->first();

        if (!$panitiaAcara) {
            return redirect()->route('dashboard')->with('error', 'Anda belum ditugaskan ke acara manapun');
        }

        // Ambil data acara yang ditugaskan
        $acara = Acara::findOrFail($panitiaAcara->id_acara);

        // Ambil data peserta yang sudah diterima
        $pesertaDiterima = Pendaftaran::with(['pengguna', 'dataPendaftaran'])
            ->where('id_acara', $acara->id)
            ->where('status', 'disetujui')
            ->orderBy('updated_at', 'desc') // Urutkan berdasarkan waktu diterima terbaru
            ->get();

        // Hitung statistik
        $jumlahPending = Pendaftaran::where('id_acara', $acara->id)->where('status', 'pending')->count();
        $jumlahDitolak = Pendaftaran::where('id_acara', $acara->id)->where('status', 'ditolak')->count();

        return view('panitia.terima-seleksi', compact('acara', 'pesertaDiterima', 'jumlahPending', 'jumlahDitolak'));
    }

    public function batalkanPenerimaan($pendaftaranId)
    {
        $user = Auth::user();
        
        // Pastikan user adalah panitia
        if ($user->peran !== 'panitia') {
            return redirect()->route('dashboard')->with('error', 'Akses ditolak');
        }

        // Cek apakah user ini terdaftar sebagai panitia
        $panitiaAcara = DB::table('panitia_acara')
            ->where('id_pengguna', $user->id)
            ->first();

        if (!$panitiaAcara) {
            return redirect()->route('dashboard')->with('error', 'Anda belum ditugaskan ke acara manapun');
        }

        // Ambil data pendaftaran dengan validasi
        $pendaftaran = Pendaftaran::where('id', $pendaftaranId)
            ->where('id_acara', $panitiaAcara->id_acara)
            ->where('status', 'disetujui') // Hanya bisa batalkan yang statusnya diterima
            ->firstOrFail();

        // Update status kembali ke pending
        $pendaftaran->update([
            'status' => 'pending',
            'alasan_penolakan' => null
        ]);

        return redirect()->route('panitia.peserta.diterima')->with('success', 'Penerimaan peserta berhasil dibatalkan! Peserta dikembalikan ke status menunggu seleksi.');
    }
}
