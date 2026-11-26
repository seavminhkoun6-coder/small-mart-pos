<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('products')) {
            Schema::table('products', function (Blueprint $table) {
                if (!Schema::hasColumn('products', 'product_name')) {
                    $table->string('product_name')->after('sku');
                }
                if (!Schema::hasColumn('products', 'cost_price')) {
                    $table->decimal('cost_price', 10, 2)->default(0)->after('product_name');
                }
                if (!Schema::hasColumn('products', 'selling_price')) {
                    $table->decimal('selling_price', 10, 2)->default(0)->after('cost_price');
                }
                if (!Schema::hasColumn('products', 'stock')) {
                    $table->integer('stock')->default(0)->after('selling_price');
                }
                if (!Schema::hasColumn('products', 'min_stock')) {
                    $table->integer('min_stock')->default(5)->after('stock');
                }
                if (!Schema::hasColumn('products', 'expiry_date')) {
                    $table->date('expiry_date')->nullable()->after('min_stock');
                }
                if (!Schema::hasColumn('products', 'status')) {
                    $table->string('status')->default('Active')->after('expiry_date');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('products')) {
            Schema::table('products', function (Blueprint $table) {
                $columns = ['product_name', 'cost_price', 'selling_price', 'stock', 'min_stock', 'expiry_date', 'status'];
                foreach ($columns as $column) {
                    if (Schema::hasColumn('products', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }
    }
};