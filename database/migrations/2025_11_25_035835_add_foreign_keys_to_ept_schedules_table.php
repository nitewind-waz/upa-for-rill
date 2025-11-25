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
        Schema::table('ept_schedules', function (Blueprint $table) {
            $table->foreign('ruang_id')->references('id')->on('ruangs')->onDelete('cascade');
            $table->foreign('gedung_id')->references('id')->on('gedungs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ept_schedules', function (Blueprint $table) {
            $table->dropForeign(['ruang_id']);
            $table->dropForeign(['gedung_id']);
        });
    }
};