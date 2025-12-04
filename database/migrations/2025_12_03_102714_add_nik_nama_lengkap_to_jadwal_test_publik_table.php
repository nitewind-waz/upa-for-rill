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
        Schema::table('jadwals_test_publik', function (Blueprint $table) {
            // Add the foreign key column
            $table->foreignId('publik_id')
                  ->nullable()
                  ->after('gedung_id') // Place it after gedung_id for logical grouping
                  ->constrained('publiks') // This sets up the foreign key constraint to the 'publiks' table
                  ->onDelete('cascade'); // If a publik is deleted, their schedules are also deleted
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jadwals_test_publik', function (Blueprint $table) {
            // Drop the foreign key constraint and the column
            $table->dropForeign(['publik_id']);
            $table->dropColumn('publik_id');
        });
    }
};
