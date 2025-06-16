<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('organizations', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('name');
            $table->json('settings')->nullable()->after('description');
            $table->boolean('is_active')->default(true)->after('settings');
            $table->string('email')->nullable()->after('is_active');
            $table->string('phone')->nullable()->after('email');
            $table->string('website')->nullable()->after('phone');
            $table->string('address')->nullable()->after('website');
            $table->string('city')->nullable()->after('address');
            $table->string('state')->nullable()->after('city');
            $table->string('country')->nullable()->after('state');
            $table->string('postal_code')->nullable()->after('country');
            $table->softDeletes()->after('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('organizations', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropColumn([
                'slug',
                'settings',
                'is_active',
                'email',
                'phone',
                'website',
                'address',
                'city',
                'state',
                'country',
                'postal_code'
            ]);
        });
    }
};
