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
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable()->unique()->after('email');
            $table->timestamp('phone_verified_at')->nullable()->after('email_verified_at');            $table->enum('primary_auth_method', [
                'email_password', 
                'email_otp', 
                'phone_password', 
                'phone_otp', 
                'google_sso',
                'magic_link'
            ])->default('email_password')->after('phone_verified_at');
        });
    }

    /**
     * Reverse the migrations.
     */    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone', 'phone_verified_at', 'primary_auth_method']);
        });
    }
};
