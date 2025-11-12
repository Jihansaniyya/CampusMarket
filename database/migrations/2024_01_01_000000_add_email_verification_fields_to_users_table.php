<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Menambahkan kolom untuk email verification token
            $table->string('email_verification_token')->nullable()->after('email_verified_at');
            // Menambahkan kolom untuk menyimpan informasi penjual
            $table->enum('role', ['buyer', 'seller'])->default('buyer')->after('email_verification_token');
            $table->string('phone')->nullable()->after('role');
            $table->text('description')->nullable()->after('phone');
            $table->string('store_name')->nullable()->after('description');
            $table->timestamp('store_verified_at')->nullable()->after('store_name');
            $table->timestamp('last_login_at')->nullable()->after('store_verified_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'email_verification_token',
                'role',
                'phone',
                'description',
                'store_name',
                'store_verified_at',
                'last_login_at',
            ]);
        });
    }
};
