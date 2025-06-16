<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{    /**
     * Run the migrations.
     */    public function up(): void
    {
        Schema::table('otps', function (Blueprint $table) {
            // Modify the type enum to include magic_link
            $table->enum('type', ['email_otp', 'phone_otp', 'magic_link'])->change();
            
            // Make otp_code nullable for magic links (they use tokens instead)
            $table->string('otp_code', 6)->nullable()->change();
            
            // Add token field for magic links (nullable for backward compatibility)
            $table->string('token', 255)->nullable()->after('otp_code');
            
            // Add IP address tracking for security
            $table->ipAddress('ip_address')->nullable()->after('identifier');
            
            // Add user agent for additional security
            $table->text('user_agent')->nullable()->after('ip_address');
            
            // Add used_at timestamp to track when the magic link was used
            $table->timestamp('used_at')->nullable()->after('verified_at');
            
            // Add index for token lookups
            $table->index('token');
        });
    }/**
     * Reverse the migrations.
     */    public function down(): void
    {
        Schema::table('otps', function (Blueprint $table) {
            // Drop the added columns
            $table->dropColumn(['token', 'ip_address', 'user_agent', 'used_at']);
            
            // Revert otp_code to NOT NULL
            $table->string('otp_code', 6)->nullable(false)->change();
            
            // Revert the type enum to original values
            $table->enum('type', ['email_otp', 'phone_otp'])->change();
            
            // Drop the token index
            $table->dropIndex(['token']);
        });
    }
};
