<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['patient', 'doctor', 'admin', 'health_worker'])
                  ->default('patient')
                  ->after('email');
            $table->string('phone')->nullable()->after('email');
            $table->boolean('phone_verified')->default(false)->after('phone');
            $table->timestamp('phone_verified_at')->nullable()->after('phone_verified');
            $table->boolean('is_active')->default(true)->after('phone_verified_at');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'phone', 'phone_verified', 'phone_verified_at', 'is_active']);
        });
    }
};
