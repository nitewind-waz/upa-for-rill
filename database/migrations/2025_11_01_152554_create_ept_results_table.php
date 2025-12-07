<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ept_results_mahasiswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswas')->cascadeOnDelete();
            $table->year('tahun');
            $table->integer('listening');
            $table->integer('structure');
            $table->integer('reading');
            $table->integer('total_score');
            $table->string('semester');

            // jangan buat kolom level di sini
            $table->string('sertifikat_pdf')->nullable();
            $table->timestamps();
        });

        // Tambahkan kolom generated setelah tabel dibuat
        DB::statement("
            ALTER TABLE ept_results_mahasiswa 
            ADD COLUMN level VARCHAR(50) GENERATED ALWAYS AS (
                CASE
                    WHEN total_score <= 393 THEN 'ELEMENTARY'
                    WHEN total_score <= 473 THEN 'LOW INTERMEDIATE'
                    WHEN total_score <= 510 THEN 'INTERMEDIATE'
                    WHEN total_score <= 587 THEN 'UPPER INTERMEDIATE'
                    ELSE 'ADVANCED'
                END
            ) STORED;
        ");
    }

    public function down(): void
    {
        Schema::dropIfExists('ept_results_mahasiswa');
    }

};
