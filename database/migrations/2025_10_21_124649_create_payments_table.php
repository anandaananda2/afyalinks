<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_id')->unique();
            
            // Relationships
            $table->foreignId('appointment_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Payment Details
            $table->decimal('amount', 10, 2);
            $table->enum('payment_method', [
                'card',
                'mobile_money',
                'bank_transfer',
                'cash'
            ]);
            
            $table->enum('status', [
                'pending',
                'processing',
                'completed',
                'failed',
                'refunded'
            ])->default('pending');
            
            // Provider Details
            $table->string('payment_provider')->nullable(); // e.g., M-Pesa, Stripe
            $table->string('provider_transaction_id')->nullable();
            $table->text('provider_response')->nullable();
            
            // Mobile Money Specific
            $table->string('phone_number')->nullable();
            
            // Metadata
            $table->text('notes')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('refunded_at')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index('transaction_id');
            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};