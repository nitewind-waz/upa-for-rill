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
        Schema::create('jadwal_test_mahasiswas', function (Blueprint $table) {
            $table->id(); // Primary Key (Surrogate Key)

            // Atribut-atribut dari 'Jadwal UPT'
            $table->date('tanggal');
            $table->time('jam_mulai');
            $table->time('jam_selesai');

            // --- Kumpulan Foreign Key (Identifying Relationships) ---

            // 1. Relasi 'Mempunyai' ke Prodi
            $table->foreignId('prodi_id')
                  ->constrained('prodis')
                  ->onDelete('cascade');

            // 2. Relasi 'Mendapatkan' ke Kelas
            $table->foreignId('kelas_id')
                  ->constrained('kelas')
                  ->onDelete('cascade');
            
            // 3. Relasi 'Menggunakan' ke Ruang
            $table->foreignId('ruang_id')
                  ->constrained('ruangs')
                  ->onDelete('cascade');

            // 4. Relasi 'Menggunakan' ke Gedung (sesuai ERD)
            $table->foreignId('gedung_id')
                  ->constrained('gedungs')
                  ->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwals');
    }
};

