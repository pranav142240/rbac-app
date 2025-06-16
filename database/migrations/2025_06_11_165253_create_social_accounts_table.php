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
        Schema::create('social_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('provider'); // 'google', 'facebook', 'apple', etc.
            $table->string('provider_id'); // Google ID, Facebook ID, etc.
            $table->string('provider_email')->nullable();
            $table->string('provider_name')->nullable();
            $table->string('provider_avatar')->nullable();
            $table->json('provider_data')->nullable(); // Store additional provider data
            $table->timestamp('linked_at')->useCurrent();
            $table->timestamps();
            
            // Ensure one provider account per user per provider
            $table->unique(['user_id', 'provider']);
            // Ensure one provider ID per provider (prevent account hijacking)
            $table->unique(['provider', 'provider_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('social_accounts');
    }
};
