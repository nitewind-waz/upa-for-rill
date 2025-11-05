<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prodis', function (Blueprint $table) {
            $table->id();
            $table->string('nama_prodi', 100);
            $table->foreignId('jurusan_id')->constrained('jurusans')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prodis');
    }
};
