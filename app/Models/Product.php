<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Order;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $fillable = [
        'order_id',
        'product_sku',
        'product_name',
        'product_value',
        'product_hsnsac',
        'product_taxper',
        'product_category',
        'product_quantity',
        'product_description',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }
}
