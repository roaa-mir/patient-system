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
        // Remove billing_id from appointments
        Schema::table('appointments', function (Blueprint $table) {
            if (Schema::hasColumn('appointments', 'billing_id')) {
                $table->dropForeign(['billing_id']);
                $table->dropColumn('billing_id');
            }
        });

        // Add appointment_id to billings
        Schema::table('billings', function (Blueprint $table) {
            $table->foreignId('appointment_id')
                ->nullable() // You can remove nullable if you want to enforce it
                ->constrained()
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Add billing_id back to appointments
        Schema::table('appointments', function (Blueprint $table) {
            $table->foreignId('billing_id')->nullable()->constrained()->onDelete('cascade');
        });

        // Remove appointment_id from billings
        Schema::table('billings', function (Blueprint $table) {
            $table->dropForeign(['appointment_id']);
            $table->dropColumn('appointment_id');
            });
    }
};
