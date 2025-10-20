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

        // Total Event dari dinas admin
        $totalEvent = Acara::where('id_dinas', $user->id_dinas)->count();
        
        // Peserta Aktif - total pendaftar dari acara dinas ini
        $pesertaAktif = Pendaftaran::whereHas('acara', function($query) use ($user) {
            $query->where('id_dinas', $user->id_dinas);
        })->where('status', 'approved')->count();
        
        // Event Selesai - acara yang tanggalnya sudah lewat
        $eventSelesai = Acara::where('id_dinas', $user->id_dinas)
            ->where('tanggal_acara', '<', now())
            ->count();

        return view('admin.dashboard', compact('totalEvent', 'pesertaAktif', 'eventSelesai'));
    }

    public function kelola()
    {
        return view('admin.kelola');
    }

    public function eventList()
    {
        $user = Auth::user();
        
        // Pastikan user adalah admin dan memiliki id_dinas
        if (!$user || !$user->id_dinas) {
            return redirect()->route('dashboard')->with('error', 'Akses ditolak');
        }

        // Ambil semua event dari dinas admin
        $events = Acara::where('id_dinas', $user->id_dinas)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.event-list', compact('events'));
    }

    public function create()
    {
        return view('admin.create');
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        
        // Pastikan user adalah admin dan memiliki id_dinas
        if (!$user || !$user->id_dinas) {
            return redirect()->route('dashboard')->with('error', 'Akses ditolak');
        }

        // Validasi data sesuai dengan migration
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'tanggal_acara' => 'required|date',
            'lokasi' => 'required|string|max:255',
            'biaya' => 'nullable|string|max:255',
            'kategori' => 'required|in:Event,Lomba',
            'sistem_pendaftaran' => 'required|in:Seleksi,Tanpa Seleksi',
            'kuota' => 'required|integer|min:1',
            'kategori_acara' => 'required|string|max:255',
            'persyaratan' => 'required|string',
            'tanggal_mulai_daftar' => 'required|date',
            'tanggal_akhir_daftar' => 'required|date|after_or_equal:tanggal_mulai_daftar',
            'hadiah' => 'required|string',
            'tentang' => 'required|string',
            'gambar' => 'required|image|mimes:jpeg,jpg,png|max:2048',
        ]);

        // Handle upload gambar
        if ($request->hasFile('gambar')) {
            $imageName = time() . '.' . $request->gambar->extension();
            $request->gambar->move(public_path('images/events'), $imageName);
        }

        // Simpan data acara sesuai dengan field migration
        $acara = Acara::create([
            'id_dinas' => $user->id_dinas,
            'judul' => $validated['judul'],
            'tanggal_acara' => $validated['tanggal_acara'],
            'lokasi' => $validated['lokasi'],
            'biaya' => $validated['biaya'] ?? 'Gratis',
            'kategori' => $validated['kategori'],
            'sistem_pendaftaran' => $validated['sistem_pendaftaran'],
            'kuota' => $validated['kuota'],
            'kategori_acara' => $validated['kategori_acara'],
            'persyaratan' => $validated['persyaratan'],
            'tanggal_mulai_daftar' => $validated['tanggal_mulai_daftar'],
            'tanggal_akhir_daftar' => $validated['tanggal_akhir_daftar'],
            'hadiah' => $validated['hadiah'],
            'tentang' => $validated['tentang'],
            'gambar' => $imageName,
            'status' => 'active'
        ]);

        // Simpan form fields ke tabel kolom_formulir_acara
        if ($request->has('form_fields')) {
            foreach ($request->form_fields as $index => $field) {
                KolomFormulirAcara::create([
                    'id_acara' => $acara->id,
                    'nama_kolom' => $field['field_name'],
                    'label_kolom' => $field['field_label'],
                    'tipe_kolom' => $field['field_type'],
                    'wajib_diisi' => (bool) $field['is_required'],
                    'placeholder' => $field['placeholder'],
                    'urutan_kolom' => $field['field_order']
                ]);
            }
        }

        return redirect()->route('admin.kelola')->with('success', 'Event berhasil dibuat!');
    }
}