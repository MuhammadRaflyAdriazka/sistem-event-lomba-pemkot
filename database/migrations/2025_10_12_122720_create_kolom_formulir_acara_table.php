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
        Schema::create('kolom_formulir_acara', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_acara')->constrained('acara')->onDelete('cascade');
            $table->string('nama_kolom'); // 'nik', 'nomor_wa', etc.
            $table->string('label_kolom'); // 'Nomor Induk Kependudukan', etc.
            $table->enum('tipe_kolom', ['text', 'email', 'number', 'file', 'textarea']);
            $table->boolean('wajib_diisi')->default(true);
            $table->string('placeholder')->nullable();
            $table->integer('urutan_kolom');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kolom_formulir_acara');
    }
};
