<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('acaras', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('gambar')->nullable();
            $table->text('deskripsi_singkat');
            $table->date('tanggal_acara');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('acaras');
    }
};
