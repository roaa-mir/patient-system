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
        Schema::create('clinics', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('address'); 
            $table->string('contact_number');
            $table->string('email')->nullable();
            $table->text('facilities')->nullable();
            $table->json('working_hours')->nullable();
            $table->boolean('is_active')->default(true);
            //$table->foreignId('doctor_id')->nullable()->constrained('doctors')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clinics');
    }
};
