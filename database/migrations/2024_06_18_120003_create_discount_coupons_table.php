<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('discount_coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('product_name')->nullable();
            $table->text('description')->nullable();
            $table->integer('max_uses')->nullable();
            $table->integer('max_users')->nullable();
            $table->enum('type', ['percentage', 'fixed'])->default('fixed');
            $table->double('discount_amount', 10, 2);
            $table->double('min_amount', 10, 2)->nullable();
            $table->integer('status')->default(1);
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('expire_at')->nullable();
            $table->integer('uses')->default(0); // New column to track total uses
            $table->integer('users_used')->default(0); // New column to track unique users used
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('discount_coupons');
    }
};
