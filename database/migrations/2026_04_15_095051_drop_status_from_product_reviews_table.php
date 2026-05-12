<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('product_reviews', 'status')) {
            Schema::table('product_reviews', function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }
        if (Schema::hasColumn('product_reviews', 'phone')) {
            Schema::table('product_reviews', function (Blueprint $table) {
                $table->dropColumn('phone');
            });
        }
        if (Schema::hasColumn('product_reviews', 'name')) {
            Schema::table('product_reviews', function (Blueprint $table) {
                $table->dropColumn('name');
            });
        }
    }

    public function down(): void
    {
        Schema::table('product_reviews', function (Blueprint $table) {
            $table->string('status')->default('approved')->nullable();
        });
    }
};
