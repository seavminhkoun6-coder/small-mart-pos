<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'sku',
        'product_name',
        'cost_price',
        'selling_price',
        'stock',
        'min_stock',
        'expiry_date',
        'status',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}