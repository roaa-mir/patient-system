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
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('firstName');
            $table->string('lastName');
            $table->date('dateOfBirth');
            $table->enum('gender',['male','female']);
            $table->integer('age');
            $table->string('bloodType');
            $table->string('phoneNumber');
            $table->string('address');
            $table->string('email');
            $table->float('weight');
            $table->float('height');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
