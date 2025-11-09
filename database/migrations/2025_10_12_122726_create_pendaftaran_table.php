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
        Schema::create('pendaftaran', function (Blueprint $table) {
             $table->id();
             $table->foreignId('id_acara')->constrained('acara')->onDelete('cascade');
             $table->foreignId('id_pengguna')->constrained('users')->onDelete('cascade');
             $table->enum('status', ['disetujui', 'pending', 'ditolak', 'mengundurkan_diri'])->default('pending');
             $table->text('alasan_penolakan')->nullable();
             $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pendaftaran');
    }
};
