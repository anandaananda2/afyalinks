<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('incident_reports', function (Blueprint $table) {
            $table->id();
            $table->string('report_number')->unique();
            $table->foreignId('reported_by')->constrained('users')->onDelete('cascade');
            
            $table->enum('category', ['drugs', 'equipment', 'supplies']);
            $table->enum('severity', ['low', 'medium', 'high', 'critical'])->default('medium');
            
            // Incident Details
            $table->string('item_name');
            $table->text('description');
            $table->integer('current_stock')->nullable();
            $table->integer('minimum_required')->nullable();
            
            $table->enum('status', [
                'reported',
                'acknowledged',
                'in_progress',
                'resolved',
                'closed'
            ])->default('reported');
            
            $table->text('resolution_notes')->nullable();
            $table->foreignId('resolved_by')->nullable()->constrained('users');
            $table->timestamp('resolved_at')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index(['status', 'severity']);
            $table->index('category');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('incident_reports');
    }
};