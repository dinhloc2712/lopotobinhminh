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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->enum('type', ['fixed', 'percent'])->default('fixed');
            $table->enum('condition_type', ['or', 'and'])->default('or');
            $table->decimal('discount_amount', 15, 2)->default(0);
            $table->integer('discount_percentage')->default(0);
            $table->decimal('max_discount_amount', 15, 2)->default(0);
            $table->decimal('min_order_value', 15, 2)->default(0);
            $table->integer('quantity')->default(0);
            $table->integer('used')->default(0);
            $table->integer('user_usage_limit')->default(1);
            $table->timestamp('start_date')->nullable();
            $table->timestamp('expiry_date')->nullable();
            $table->string('status')->default('active');
            $table->integer('priority')->default(0);
            $table->timestamps();
        });

        Schema::create('coupon_product', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coupon_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupon_product');
        Schema::dropIfExists('coupons');
    }
};
