<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('products')) {
            Schema::create('products', function (Blueprint $table) {
                $table->id();
                // រក្សា columns ដើមរបស់អ្នកនៅទីនេះ
                $table->integer('stock')->default(0);
                $table->integer('min_stock')->default(5);
                $table->date('expiry_date')->nullable();
                $table->string('status')->default('Active');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};