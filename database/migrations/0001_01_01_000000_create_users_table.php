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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('phone')->nullable()->unique();
            $table->string('avatar')->nullable();
            $table->unsignedBigInteger('role_id')->nullable();
            
            // Status/Location
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('province_id')->nullable();
            $table->unsignedBigInteger('ward_id')->nullable();
            $table->string('street_address')->nullable();
            
            // Employee / HR fields
            $table->string('code')->nullable()->unique(); // Staff ID
            $table->string('position')->nullable();
            $table->decimal('salary_base', 15, 2)->default(0);
            $table->date('start_date')->nullable();
            $table->unsignedBigInteger('branch_id')->nullable(); // Fixed: branch is kept, department is gone

            // Company Info (for B2B/Client users)
            $table->string('company_name')->nullable();
            $table->string('tax_code')->nullable();
            $table->string('bank_account')->nullable();
            $table->string('bank_name')->nullable();
            $table->decimal('commission_rate', 5, 2)->default(5.00);

            // Digital Signature (Mat Bao & MySign)
            $table->string('matbao_taxcode')->nullable();
            $table->string('matbao_username')->nullable();
            $table->string('matbao_password')->nullable();
            $table->string('matbao_signature_image')->nullable();
            $table->string('mysign_client_id')->nullable();
            $table->string('mysign_client_secret')->nullable();
            $table->string('mysign_profile_id')->nullable();
            $table->string('mysign_user_id')->nullable();
            $table->string('mysign_credential_id')->nullable();
            $table->string('mysign_signature_image')->nullable();

            // Education background
            $table->string('degree')->nullable();
            $table->string('major')->nullable();

            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('failed_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->text('connection');
            $table->text('queue');
            $table->longText('payload');
            $table->longText('exception');
            $table->timestamp('failed_at')->useCurrent();
        });

        Schema::create('personal_access_tokens', function (Blueprint $table) {
            $table->id();
            $table->morphs('tokenable');
            $table->string('name');
            $table->string('token', 64)->unique();
            $table->text('abilities')->nullable();
            $table->timestamp('last_used_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('failed_jobs');
        Schema::dropIfExists('personal_access_tokens');
    }
};
