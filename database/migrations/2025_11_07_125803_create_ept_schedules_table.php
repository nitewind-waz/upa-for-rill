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
        Schema::create('ept_schedules', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->time('waktu_mulai');
            $table->time('waktu_selesai');

            $table->foreignId('jurusan_id')
                  ->constrained('jurusans')
                  ->onDelete('cascade');

            $table->foreignId('prodi_id')
                  ->constrained('prodis')
                  ->onDelete('cascade');

            $table->foreignId('kelas_id')
                  ->constrained('kelas')
                  ->onDelete('cascade');
            
            $table->foreignId('ruang_id')
                  ->constrained('ruangs')
                  ->onDelete('cascade');

            $table->foreignId('gedung_id')
                  ->constrained('gedungs')
                  ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ept_schedules');
    }
};
