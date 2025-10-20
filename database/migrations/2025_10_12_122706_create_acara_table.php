<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('acara', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_dinas')->constrained('dinas')->onDelete('cascade');
            $table->string('judul');
            $table->date('tanggal_acara');
            $table->string('lokasi');
            $table->string('biaya')->default('Gratis');
            $table->enum('kategori', ['Event', 'Lomba']);
            $table->enum('sistem_pendaftaran', ['Seleksi', 'Tanpa Seleksi']);
            $table->integer('kuota');
            $table->string('kategori_acara');
            $table->text('persyaratan');
            $table->date('tanggal_mulai_daftar');
            $table->date('tanggal_akhir_daftar');
            $table->text('hadiah');
            $table->text('tentang');
            $table->string('gambar');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('acara');
    }
};
