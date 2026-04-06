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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('category', ['studyAbroad', 'job', 'language', 'tourism']);
            $table->decimal('amount', 15, 2)->default(0);
            $table->decimal('commission', 15, 2)->default(0);
            $table->string('duration')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_code')->unique();
            $table->enum('status', ['pending', 'completed', 'cancelled'])->default('pending');
            $table->foreignId('service_id')->constrained('services')->onDelete('cascade');
            $table->foreignId('customer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('referrer_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });

        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['income', 'expense']);
            $table->decimal('amount', 15, 2);
            $table->text('description')->nullable();
            $table->string('reference_id')->nullable();
            $table->string('reference_type')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->dateTime('transaction_date');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });

        Schema::create('payment_receipts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->date('payment_date')->nullable();
            $table->decimal('amount', 15, 2)->default(0);
            $table->enum('payment_method', ['bank_transfer', 'cash', 'installment'])->default('cash');
            $table->text('note')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_receipts');
        Schema::dropIfExists('transactions');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('services');
    }
};
