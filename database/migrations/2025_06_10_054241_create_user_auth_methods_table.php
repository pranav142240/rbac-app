<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */    public function up(): void
    {
        Schema::create('user_auth_methods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('auth_method_type', [
                'email_password', 
                'email_otp', 
                'phone_password', 
                'phone_otp', 
                'google_sso',
                'magic_link'
            ]);
            $table->string('identifier'); // email or phone
            $table->boolean('is_active')->default(true);
            $table->timestamp('auth_method_verified_at')->nullable();
            $table->string('provider_id')->nullable(); // For SSO
            $table->json('provider_data')->nullable(); // Additional SSO data
            $table->timestamps();

            $table->index(['user_id', 'auth_method_type']);
            $table->index(['identifier', 'auth_method_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_auth_methods');
    }
};
