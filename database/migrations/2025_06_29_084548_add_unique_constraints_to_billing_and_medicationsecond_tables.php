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
         Schema::table('billings', function (Blueprint $table) {
            // Unique index on appointment_id and title (billing)
            $table->unique(['appointment_id', 'titlee']);
        });

        Schema::table('medications', function (Blueprint $table) {
            // Unique index on appointment_id and name (medication)
            $table->unique(['appointment_id', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('billings', function (Blueprint $table) {
            $table->dropUnique(['appointment_id', 'titlee']);
        });

        Schema::table('medications', function (Blueprint $table) {
            $table->dropUnique(['appointment_id', 'name']);
        });
    }
};
