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
        Schema::create('pengumuman', function (Blueprint $table) {
             $table->id();
             $table->foreignId('id_acara')->constrained('acara')->onDelete('cascade');
             $table->foreignId('id_pengguna')->constrained('users')->onDelete('cascade'); // Pembuat pengumuman (panitia/admin)
             $table->text('isi');
             $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengumuman');
    }
};
