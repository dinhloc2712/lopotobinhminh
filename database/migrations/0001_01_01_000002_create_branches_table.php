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
        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('Tên chi nhánh');
            $table->string('code')->nullable()->unique()->comment('Mã chi nhánh');
            $table->string('address')->nullable()->comment('Địa chỉ');
            $table->string('phone')->nullable()->comment('Số điện thoại');
            $table->string('email')->nullable()->comment('Email liên hệ');
            $table->string('manager_name')->nullable()->comment('Tên người quản lý');
            $table->boolean('is_active')->default(true)->comment('Trạng thái hoạt động');
            $table->text('notes')->nullable()->comment('Ghi chú');
            $table->timestamps();
        });

        // Add foreign key to users table now that both exist
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('set null');
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['branch_id']);
            $table->dropForeign(['role_id']);
        });
        Schema::dropIfExists('branches');
    }
};
