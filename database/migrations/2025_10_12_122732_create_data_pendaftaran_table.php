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
        Schema::create('data_pendaftaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pendaftaran')->constrained('pendaftaran')->onDelete('cascade');
            $table->string('nama_kolom');
            $table->text('nilai_kolom'); // Bisa berisi teks jawaban atau path file
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_pendaftaran');
    }
};
