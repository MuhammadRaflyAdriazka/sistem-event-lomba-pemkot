<?php
require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== DEBUG DATA PENDAFTARAN ===\n";

// Check data di tabel-tabel
echo "Total acara: " . \DB::table('acara')->count() . "\n";
echo "Total kolom formulir: " . \DB::table('kolom_formulir_acara')->count() . "\n";
echo "Total pendaftaran: " . \DB::table('pendaftaran')->count() . "\n";
echo "Total data pendaftaran: " . \DB::table('data_pendaftaran')->count() . "\n";

echo "\n=== DETAIL ACARA ===\n";
$acara = \DB::table('acara')->first();
if ($acara) {
    echo "Acara ID: {$acara->id}\n";
    echo "Acara Judul: {$acara->judul}\n";
    
    echo "\n=== KOLOM FORMULIR UNTUK ACARA INI ===\n";
    $koloms = \DB::table('kolom_formulir_acara')->where('id_acara', $acara->id)->get();
    echo "Jumlah kolom: " . $koloms->count() . "\n";
    
    foreach ($koloms as $kolom) {
        echo "- {$kolom->nama_kolom} ({$kolom->label_kolom}) - {$kolom->tipe_kolom}\n";
    }
}