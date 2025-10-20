<?php

namespace App\Http\Controllers;

use App\Models\Acara;
use App\Models\Pendaftaran;
use App\Models\DataPendaftaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class PendaftaranController extends Controller
{
    /**
     * Menampilkan form pendaftaran untuk acara tertentu
     */
    public function show(Acara $acara)
    {
        // Cek apakah acara masih aktif
        if ($acara->status !== 'active') {
            abort(404, 'Acara tidak tersedia');
        }

        // Cek apakah periode pendaftaran masih buka
        $sekarang = Carbon::now();
        if (!$sekarang->between($acara->tanggal_mulai_daftar, $acara->tanggal_akhir_daftar)) {
            return redirect()->route('acara.show', $acara->id)
                           ->with('error', 'Periode pendaftaran sudah ditutup');
        }

        // Cek apakah user sudah terdaftar
        $sudahDaftar = Pendaftaran::where('id_acara', $acara->id)
                                 ->where('id_pengguna', auth()->id())
                                 ->exists();

        if ($sudahDaftar) {
            return redirect()->route('acara.show', $acara->id)
                           ->with('info', 'Anda sudah terdaftar pada acara ini');
        }

        return view('pendaftaran', compact('acara'));
    }

    /**
     * Menyimpan data pendaftaran
     */
    public function store(Request $request, Acara $acara)
    {
        // Validasi dasar
        if ($acara->status !== 'active') {
            return back()->with('error', 'Acara tidak tersedia');
        }

        $sekarang = Carbon::now();
        if (!$sekarang->between($acara->tanggal_mulai_daftar, $acara->tanggal_akhir_daftar)) {
            return back()->with('error', 'Periode pendaftaran sudah ditutup');
        }

        // Cek apakah user sudah terdaftar
        $sudahDaftar = Pendaftaran::where('id_acara', $acara->id)
                                 ->where('id_pengguna', auth()->id())
                                 ->exists();

        if ($sudahDaftar) {
            return back()->with('error', 'Anda sudah terdaftar pada acara ini');
        }

        // Buat validasi rules berdasarkan kolom formulir
        $rules = [];
        foreach ($acara->kolomFormulir as $field) {
            $fieldRules = [];
            
            if ($field->wajib_diisi) {
                $fieldRules[] = 'required';
            }
            
            if ($field->tipe_kolom === 'file') {
                $fieldRules[] = 'file';
                $fieldRules[] = 'mimes:jpg,jpeg,png,pdf';
                $fieldRules[] = 'max:2048'; // 2MB
            }
            
            if ($field->tipe_kolom === 'number') {
                $fieldRules[] = 'numeric';
            }
            
            $rules[$field->nama_kolom] = implode('|', $fieldRules);
        }

        // Validasi request
        $validatedData = $request->validate($rules);

        try {
            // Buat record pendaftaran
            $pendaftaran = Pendaftaran::create([
                'id_acara' => $acara->id,
                'id_pengguna' => auth()->id(),
                'status' => 'pending'
            ]);

            \Log::info('Pendaftaran berhasil dibuat:', ['id' => $pendaftaran->id]);
            \Log::info('Jumlah kolom formulir:', ['count' => $acara->kolomFormulir->count()]);

            // Simpan data form ke data_pendaftaran
            foreach ($acara->kolomFormulir as $field) {
                \Log::info('Processing field:', ['nama_kolom' => $field->nama_kolom]);
                
                $nilai = $request->input($field->nama_kolom);
                
                // Handle file upload
                if ($field->tipe_kolom === 'file' && $request->hasFile($field->nama_kolom)) {
                    $file = $request->file($field->nama_kolom);
                    $filename = time() . '_' . $field->nama_kolom . '.' . $file->getClientOriginalExtension();
                    $file->storeAs('public/pendaftaran', $filename);
                    $nilai = $filename;
                }

                // Skip jika nilai kosong untuk field yang tidak wajib
                if (empty($nilai) && !$field->wajib_diisi) {
                    \Log::info('Skipping empty non-required field:', ['nama_kolom' => $field->nama_kolom]);
                    continue;
                }

                // Simpan data dengan debug
                \Log::info('Menyimpan data pendaftaran:', [
                    'id_pendaftaran' => $pendaftaran->id,
                    'nama_kolom' => $field->nama_kolom,
                    'nilai' => $nilai
                ]);

                $dataResult = DataPendaftaran::create([
                    'id_pendaftaran' => $pendaftaran->id,
                    'nama_kolom' => $field->nama_kolom,
                    'nilai_kolom' => $nilai ?? ''
                ]);
                
                \Log::info('Data berhasil disimpan:', ['data_id' => $dataResult->id]);
            }

            return redirect()->route('acara')->with('success', 'Pendaftaran berhasil! Terima kasih telah mendaftar.');

        } catch (\Exception $e) {
            \Log::error('Error saat menyimpan pendaftaran: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            return back()->with('error', 'Terjadi kesalahan saat memproses pendaftaran: ' . $e->getMessage());
        }
    }
}
