<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('doctor_availabilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doctor_id')->constrained('users')->onDelete('cascade');
            
            $table->date('date');
            $table->time('start_time');
            $table->time('end_time');
            
            $table->boolean('is_available')->default(true);
            $table->enum('type', ['regular', 'special', 'leave'])->default('regular');
            $table->text('notes')->nullable();
            
            $table->timestamps();
            
            // Ensure no overlapping availability for same doctor
            $table->unique(['doctor_id', 'date', 'start_time']);
            
            // Indexes
            $table->index(['doctor_id', 'date', 'is_available']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('doctor_availabilities');
    }
};