<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('appointment_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('appointment_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            $table->enum('type', [
                'appointment_confirmation',
                'reminder_24h',
                'reminder_2h',
                'appointment_cancelled',
                'appointment_rescheduled',
                'payment_reminder'
            ]);
            
            $table->enum('channel', ['email', 'sms', 'both']);
            $table->enum('status', ['pending', 'sent', 'failed'])->default('pending');
            
            $table->timestamp('scheduled_for');
            $table->timestamp('sent_at')->nullable();
            
            $table->text('message')->nullable();
            $table->text('error_message')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index(['scheduled_for', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointment_notifications');
    }
};