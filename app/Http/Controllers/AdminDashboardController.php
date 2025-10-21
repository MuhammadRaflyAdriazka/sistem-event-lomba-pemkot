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

        return view('admin.acara', compact('events'));
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

    public function edit($id)
    {
        $user = Auth::user();
        
        // Pastikan user adalah admin dan memiliki id_dinas
        if (!$user || !$user->id_dinas) {
            return redirect()->route('dashboard')->with('error', 'Akses ditolak');
        }

        // Ambil acara berdasarkan id dan pastikan milik dinas admin ini
        $acara = Acara::where('id', $id)
            ->where('id_dinas', $user->id_dinas)
            ->firstOrFail();

        // Ambil kolom formulir untuk acara ini
        $kolomFormulir = KolomFormulirAcara::where('id_acara', $id)
            ->orderBy('urutan_kolom')
            ->get();

        return view('admin.edit', compact('acara', 'kolomFormulir'));
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();
        
        // Pastikan user adalah admin dan memiliki id_dinas
        if (!$user || !$user->id_dinas) {
            return redirect()->route('dashboard')->with('error', 'Akses ditolak');
        }

        // Ambil acara berdasarkan id dan pastikan milik dinas admin ini
        $acara = Acara::where('id', $id)
            ->where('id_dinas', $user->id_dinas)
            ->firstOrFail();

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
            'gambar' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
        ]);

        // Handle upload gambar jika ada file baru
        if ($request->hasFile('gambar')) {
            // Hapus gambar lama jika ada
            if ($acara->gambar && file_exists(public_path('images/events/' . $acara->gambar))) {
                unlink(public_path('images/events/' . $acara->gambar));
            }
            
            $imageName = time() . '.' . $request->gambar->extension();
            $request->gambar->move(public_path('images/events'), $imageName);
            $validated['gambar'] = $imageName;
        }

        // Update data acara
        $acara->update($validated);

        // Update form fields jika ada perubahan
        if ($request->has('form_fields')) {
            // Hapus kolom formulir lama (kecuali yang default)
            KolomFormulirAcara::where('id_acara', $id)
                ->whereNotIn('nama_kolom', ['nama_lengkap', 'no_hp', 'email', 'alamat'])
                ->delete();

            // Simpan form fields baru
            foreach ($request->form_fields as $index => $field) {
                // Skip field default yang sudah ada
                if (in_array($field['field_name'], ['nama_lengkap', 'no_hp', 'email', 'alamat'])) {
                    continue;
                }

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

        return redirect()->route('admin.event')->with('success', 'Event berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $user = Auth::user();
        
        // Pastikan user adalah admin dan memiliki id_dinas
        if (!$user || !$user->id_dinas) {
            return redirect()->route('dashboard')->with('error', 'Akses ditolak');
        }

        // Ambil acara berdasarkan id dan pastikan milik dinas admin ini
        $acara = Acara::where('id', $id)
            ->where('id_dinas', $user->id_dinas)
            ->firstOrFail();

        // Hapus gambar jika ada
        if ($acara->gambar && file_exists(public_path('images/events/' . $acara->gambar))) {
            unlink(public_path('images/events/' . $acara->gambar));
        }

        // Hapus kolom formulir terkait
        KolomFormulirAcara::where('id_acara', $id)->delete();

        // Hapus acara
        $acara->delete();

        return redirect()->route('admin.event')->with('success', 'Event berhasil dihapus!');
    }

    public function markAsFinished($id)
    {
        $user = Auth::user();
        
        // Pastikan user adalah admin dan memiliki id_dinas
        if (!$user || !$user->id_dinas) {
            return redirect()->route('dashboard')->with('error', 'Akses ditolak');
        }

        // Ambil acara berdasarkan id dan pastikan milik dinas admin ini
        $acara = Acara::where('id', $id)
            ->where('id_dinas', $user->id_dinas)
            ->firstOrFail();

        // Update status menjadi inactive (selesai)
        $acara->update(['status' => 'inactive']);

        return redirect()->route('admin.event')->with('success', 'Event berhasil ditandai sebagai selesai!');
    }
}