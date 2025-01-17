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
        if (!Schema::hasTable('products')) {
            Schema::create('products', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->string('slug');
                $table->text('description')->nullable();
                $table->string('image')->nullable()->change();
                $table->double('price', 10, 2);
                $table->double('compare_price', 10, 2)->nullable();
                $table->foreignId('category_id')->nullable()->constrained()->onDelete('cascade');
                $table->foreignId('sub_category_id')->nullable()->constrained()->onDelete('cascade');
                $table->foreignId('brand_id')->nullable()->constrained()->onDelete('cascade');
                $table->string('size')->nullable();
                $table->string('color')->nullable();
                $table->enum('is_featured', ['yes', 'no'])->default('no');
                $table->string('sku');
                $table->string('barcode')->nullable();
                $table->enum('track_qty', ['yes', 'no'])->default('yes');
                $table->integer('qty')->nullable();
                $table->integer('status')->default(1);
                $table->timestamps();
              ;

            });
        }
    }

    /**
     * Reverse the migrations.
     */
     public function down(): void
    {
        Schema::dropIfExists('products');


    }
};
