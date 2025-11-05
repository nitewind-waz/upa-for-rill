<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->text('deskripsi');
            $table->text('ketentuan')->nullable();
            $table->text('sistem_pembelajaran')->nullable();
            $table->string('jadwal')->nullable();
            $table->enum('jenis', ['Gratis', 'Berbayar'])->default('Gratis');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
