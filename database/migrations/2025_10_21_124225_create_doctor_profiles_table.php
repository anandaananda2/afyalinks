<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('doctor_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Professional Information
            $table->string('specialization');
            $table->string('license_number')->unique();
            $table->text('qualifications')->nullable();
            $table->text('bio')->nullable();
            $table->string('profile_photo')->nullable();
            
            // Consultation Settings
            $table->decimal('consultation_fee', 10, 2)->default(0);
            $table->integer('consultation_duration')->default(30); // minutes
            
            // Availability
            $table->time('work_start_time')->default('08:00:00');
            $table->time('work_end_time')->default('17:00:00');
            $table->time('break_start_time')->nullable();
            $table->time('break_end_time')->nullable();
            
            $table->json('working_days')->nullable(); // [1,2,3,4,5] for Mon-Fri
            
            $table->boolean('is_available')->default(true);
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('doctor_profiles');
    }
};