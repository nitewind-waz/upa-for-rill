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
            $table->dropForeign(['publik_id']);
            $table->dropColumn('publik_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ept_schedules', function (Blueprint $table) {
            $table->unsignedBigInteger('publik_id')->nullable()->after('id');
            $table->foreign('publik_id')->references('id')->on('publiks')->onDelete('cascade');
        });
    }
};
