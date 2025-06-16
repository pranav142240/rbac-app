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
        Schema::create('otps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('otp_code', 6);
            $table->enum('type', ['email_otp', 'phone_otp']);
            $table->string('identifier'); // email or phone
            $table->timestamp('expires_at');
            $table->timestamp('verified_at')->nullable();
            $table->integer('attempts')->default(0);
            $table->timestamps();

            $table->index(['identifier', 'otp_code']);
            $table->index(['user_id', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('otps');
    }
};
