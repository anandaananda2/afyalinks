<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->string('appointment_number')->unique();
            
            // Relationships
            $table->foreignId('patient_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('doctor_id')->constrained('users')->onDelete('cascade');
            
            // Appointment Details
            $table->date('appointment_date');
            $table->time('appointment_time');
            $table->integer('duration')->default(30); // minutes
            
            $table->enum('type', ['consultation', 'follow_up', 'emergency'])->default('consultation');
            $table->enum('status', [
                'pending',
                'confirmed', 
                'checked_in',
                'in_progress',
                'completed',
                'cancelled',
                'no_show'
            ])->default('pending');
            
            // Payment
            $table->decimal('consultation_fee', 10, 2);
            $table->enum('payment_status', ['pending', 'paid', 'refunded'])->default('pending');
            
            // Additional Info
            $table->text('reason')->nullable();
            $table->text('notes')->nullable();
            $table->text('cancellation_reason')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            
            // Timing
            $table->timestamp('checked_in_at')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index(['appointment_date', 'doctor_id']);
            $table->index(['patient_id', 'appointment_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};