<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Acara;
use App\Models\Pendaftaran;
use App\Models\Pengumuman;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PanitiaController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Pastikan user adalah panitia
        if ($user->peran !== 'panitia') {
            return redirect()->route('home')->with('error', 'Akses ditolak - Anda bukan panitia');
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
        $jumlahMengundurkanDiri = Pendaftaran::where('id_acara', $acara->id)->where('status', 'mengundurkan_diri')->count();

        return view('panitia.peserta-seleksi', compact('acara', 'peserta', 'totalPendaftar', 'jumlahDiterima', 'jumlahDitolak', 'jumlahMengundurkanDiri'));
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

    public function tolakPeserta(Request $request, $pendaftaranId)
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

        // Validasi input alasan penolakan
        $request->validate([
            'alasan_penolakan' => 'required|string|min:5|max:255'
        ], [
            'alasan_penolakan.required' => 'Alasan penolakan wajib diisi',
            'alasan_penolakan.min' => 'Alasan penolakan minimal 5 karakter',
            'alasan_penolakan.max' => 'Alasan penolakan maksimal 255 karakter'
        ]);

        // Update status menjadi ditolak dengan alasan dari input
        $pendaftaran->update([
            'status' => 'ditolak',
            'alasan_penolakan' => $request->alasan_penolakan
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
        $jumlahMengundurkanDiri = Pendaftaran::where('id_acara', $acara->id)->where('status', 'mengundurkan_diri')->count();

        return view('panitia.terima-seleksi', compact('acara', 'pesertaDiterima', 'jumlahPending', 'jumlahDitolak', 'jumlahMengundurkanDiri'));
    }

    public function pesertaDitolak()
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
        
        // Pastikan acara menggunakan sistem seleksi
        if ($acara->sistem_pendaftaran !== 'Seleksi') {
            return redirect()->route('panitia.peserta')->with('error', 'Fitur ini hanya untuk acara dengan sistem seleksi');
        }

        // Ambil data peserta yang ditolak
        $pesertaDitolak = Pendaftaran::with(['pengguna', 'dataPendaftaran'])
            ->where('id_acara', $acara->id)
            ->where('status', 'ditolak')
            ->orderBy('updated_at', 'desc')
            ->get();

        // Hitung statistik
        $jumlahPending = Pendaftaran::where('id_acara', $acara->id)->where('status', 'pending')->count();
        $jumlahDiterima = Pendaftaran::where('id_acara', $acara->id)->where('status', 'disetujui')->count();
        $jumlahMengundurkanDiri = Pendaftaran::where('id_acara', $acara->id)->where('status', 'mengundurkan_diri')->count();

        return view('panitia.ditolak-seleksi', compact('acara', 'pesertaDitolak', 'jumlahPending', 'jumlahDiterima', 'jumlahMengundurkanDiri'));
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

    public function batalkanPenolakan($pendaftaranId)
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
            ->where('status', 'ditolak')
            ->firstOrFail();

        // Update status kembali ke pending dan hapus alasan penolakan
        $pendaftaran->update([
            'status' => 'pending',
            'alasan_penolakan' => null
        ]);

        return redirect()->route('panitia.peserta.ditolak')->with('success', 'Penolakan berhasil dibatalkan! Peserta dikembalikan ke status menunggu seleksi.');
    }

    public function tolakMassalKuotaPenuh()
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

        // Pastikan ini acara seleksi
        if ($acara->sistem_pendaftaran !== 'Seleksi') {
            return redirect()->route('panitia.peserta')->with('error', 'Fitur ini hanya untuk acara dengan sistem seleksi');
        }

        // Tolak semua peserta yang masih pending dengan alasan kuota penuh
        $updated = Pendaftaran::where('id_acara', $acara->id)
            ->where('status', 'pending')
            ->update([
                'status' => 'ditolak',
                'alasan_penolakan' => 'Maaf, kuota sudah terpenuhi'
            ]);

        if ($updated > 0) {
            return redirect()->route('panitia.peserta')->with('success', "Berhasil menolak {$updated} sisa peserta dengan alasan: Kuota sudah terpenuhi");
        } else {
            return redirect()->route('panitia.peserta')->with('info', 'Tidak ada peserta yang perlu ditolak (semua sudah diproses)');
        }
    }

    /**
     * Menampilkan halaman kelola peserta untuk sistem TANPA SELEKSI
     * Peserta yang mendaftar langsung diterima otomatis (first come first served)
     * sampai kuota penuh
     */
    public function pesertaTanpaSeleksi()
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
        
        // Pastikan acara ini menggunakan sistem tanpa seleksi
        if ($acara->sistem_pendaftaran !== 'Tanpa Seleksi') {
            return redirect()->route('panitia.peserta')->with('error', 'Acara ini menggunakan sistem seleksi, bukan tanpa seleksi');
        }

        // Auto-approve peserta yang masih pending di sistem tanpa seleksi
        $pendingPeserta = Pendaftaran::where('id_acara', $acara->id)
            ->where('status', 'pending')
            ->get();

        foreach ($pendingPeserta as $pendaftaran) {
            // Cek apakah kuota masih tersedia
            $jumlahDiterima = Pendaftaran::where('id_acara', $acara->id)
                ->where('status', 'disetujui')
                ->count();
                
            if ($jumlahDiterima < $acara->kuota) {
                $pendaftaran->update(['status' => 'disetujui']);
            }
        }

        // Ambil data peserta yang sudah diterima otomatis (status disetujui)
        $pesertaDiterima = Pendaftaran::with(['pengguna', 'dataPendaftaran'])
            ->where('id_acara', $acara->id)
            ->where('status', 'disetujui')
            ->orderBy('created_at', 'asc') // Urutkan berdasarkan waktu daftar (first come first served)
            ->get();

        // Hitung statistik
        $totalPendaftar = Pendaftaran::where('id_acara', $acara->id)->count();
        $jumlahDiterima = Pendaftaran::where('id_acara', $acara->id)->where('status', 'disetujui')->count();
        $jumlahDitolak = Pendaftaran::where('id_acara', $acara->id)->where('status', 'ditolak')->count();
        $jumlahMengundurkanDiri = Pendaftaran::where('id_acara', $acara->id)->where('status', 'mengundurkan_diri')->count();
        $kuotaTersisa = $acara->kuota - $jumlahDiterima;

        return view('panitia.peserta-tanpa-seleksi', compact('acara', 'pesertaDiterima', 'totalPendaftar', 'jumlahDiterima', 'jumlahDitolak', 'jumlahMengundurkanDiri', 'kuotaTersisa'));
    }

    /**
     * Batalkan penerimaan peserta pada sistem TANPA SELEKSI
     * Digunakan jika ada peserta yang tidak memenuhi syarat setelah dicek
     */
    public function batalkanPenerimaanTanpaSeleksi(Request $request, $pendaftaranId)
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

        // Validasi alasan pembatalan wajib diisi
        $request->validate([
            'alasan_pembatalan' => 'required|string|min:10|max:500'
        ], [
            'alasan_pembatalan.required' => 'Alasan pembatalan wajib diisi',
            'alasan_pembatalan.min' => 'Alasan pembatalan minimal 10 karakter',
            'alasan_pembatalan.max' => 'Alasan pembatalan maksimal 500 karakter'
        ]);

        // Update status menjadi ditolak dengan alasan pembatalan
        $pendaftaran->update([
            'status' => 'ditolak',
            'alasan_penolakan' => $request->alasan_pembatalan
        ]);

        return redirect()->route('panitia.peserta.tanpaSeleksi')->with('success', 'Penerimaan peserta berhasil dibatalkan dan peserta akan diberitahu melalui status di halaman "Acara Saya".');
    }

    /**
     * Menampilkan detail peserta untuk sistem TANPA SELEKSI
     */
    public function detailPesertaTanpaSeleksi($pendaftaranId)
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

        // Pastikan acara menggunakan sistem tanpa seleksi
        if ($pendaftaran->acara->sistem_pendaftaran !== 'Tanpa Seleksi') {
            return redirect()->route('panitia.peserta')->with('error', 'Pendaftaran ini bukan untuk sistem tanpa seleksi');
        }

        // Ambil kolom formulir untuk acara ini
        $kolomFormulir = DB::table('kolom_formulir_acara')
            ->where('id_acara', $pendaftaran->id_acara)
            ->orderBy('urutan_kolom')
            ->get();

        return view('panitia.detail-tanpa-seleksi', compact('pendaftaran', 'kolomFormulir'));
    }

    public function pesertaDitolakTanpaSeleksi()
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
        
        // Pastikan acara menggunakan sistem tanpa seleksi
        if ($acara->sistem_pendaftaran !== 'Tanpa Seleksi') {
            return redirect()->route('panitia.peserta')->with('error', 'Fitur ini hanya untuk acara dengan sistem tanpa seleksi');
        }

        // Ambil data peserta yang ditolak
        $pesertaDitolak = Pendaftaran::with(['pengguna', 'dataPendaftaran'])
            ->where('id_acara', $acara->id)
            ->where('status', 'ditolak')
            ->orderBy('updated_at', 'desc')
            ->get();

        // Hitung statistik
        $jumlahPending = Pendaftaran::where('id_acara', $acara->id)->where('status', 'pending')->count();
        $jumlahDiterima = Pendaftaran::where('id_acara', $acara->id)->where('status', 'disetujui')->count();
        $jumlahMengundurkanDiri = Pendaftaran::where('id_acara', $acara->id)->where('status', 'mengundurkan_diri')->count();

        return view('panitia.ditolak-tanpa-seleksi', compact('acara', 'pesertaDitolak', 'jumlahPending', 'jumlahDiterima', 'jumlahMengundurkanDiri'));
    }

    public function batalkanPenolakanTanpaSeleksi($pendaftaranId)
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
            ->where('status', 'ditolak')
            ->firstOrFail();

        // Ambil data acara untuk cek kuota
        $acara = Acara::findOrFail($panitiaAcara->id_acara);
        
        // Cek kuota tersedia
        $jumlahDiterima = Pendaftaran::where('id_acara', $acara->id)
            ->where('status', 'disetujui')
            ->count();

        if ($jumlahDiterima >= $acara->kuota) {
            return redirect()->route('panitia.peserta.ditolakTanpaSeleksi')
                ->with('error', 'Tidak dapat menerima peserta karena kuota sudah penuh.');
        }

        // Update status langsung ke disetujui (bukan pending) dan hapus alasan penolakan
        $pendaftaran->update([
            'status' => 'disetujui',
            'alasan_penolakan' => null
        ]);

        return redirect()->route('panitia.peserta.ditolakTanpaSeleksi')->with('success', 'Peserta berhasil diterima kembali!');
    }

    public function pengumuman()
    {
        $user = Auth::user();
        
        // Pastikan user adalah panitia
        if ($user->peran !== 'panitia') {
            return redirect()->route('home')->with('error', 'Akses ditolak - Anda bukan panitia');
        }

        // Ambil acara yang ditugaskan untuk panitia ini
        $panitiaAcara = DB::table('panitia_acara')->where('id_pengguna', $user->id)->first();
        
        if (!$panitiaAcara) {
            return redirect()->route('home')->with('error', 'Anda belum ditugaskan untuk mengelola acara apapun.');
        }

        $acara = Acara::findOrFail($panitiaAcara->id_acara);

        // Ambil semua pengumuman untuk acara ini
        $pengumumanList = Pengumuman::where('id_acara', $acara->id)
                                  ->orderBy('created_at', 'desc')
                                  ->get();

        return view('panitia.pengumuman', compact('acara', 'pengumumanList'));
    }

    public function storePengumuman(Request $request)
    {
        $user = Auth::user();
        
        // Pastikan user adalah panitia
        if ($user->peran !== 'panitia') {
            return redirect()->route('home')->with('error', 'Akses ditolak - Anda bukan panitia');
        }

        // Ambil acara yang ditugaskan untuk panitia ini
        $panitiaAcara = DB::table('panitia_acara')->where('id_pengguna', $user->id)->first();
        
        if (!$panitiaAcara) {
            return redirect()->route('home')->with('error', 'Anda belum ditugaskan untuk mengelola acara apapun.');
        }

        $request->validate([
            'isi' => 'required|string'
        ]);

        // Cek apakah ini mode update atau create baru
        if ($request->has('update_mode')) {
            // Update pengumuman yang sudah ada
            $pengumuman = Pengumuman::where('id_acara', $panitiaAcara->id_acara)->first();
            if ($pengumuman) {
                $pengumuman->update([
                    'isi' => $request->isi,
                    'updated_at' => now()
                ]);
                return redirect()->route('panitia.pengumuman')->with('success', 'Pengumuman berhasil diperbarui!');
            }
        } else {
            // Buat pengumuman baru (hanya jika belum ada)
            $existingPengumuman = Pengumuman::where('id_acara', $panitiaAcara->id_acara)->first();
            if (!$existingPengumuman) {
                Pengumuman::create([
                    'id_acara' => $panitiaAcara->id_acara,
                    'id_pengguna' => $user->id,
                    'isi' => $request->isi
                ]);
                return redirect()->route('panitia.pengumuman')->with('success', 'Pengumuman berhasil dibuat!');
            }
        }

        return redirect()->route('panitia.pengumuman')->with('error', 'Terjadi kesalahan!');
    }
}
