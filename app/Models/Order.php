<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Product;

class Order extends Model
{
    use HasFactory;

    protected $table = 'shiparcel_orders';

    protected $fillable = [
        'address_title',
        'sender_name',
        'full_address',
        'phone',
        'pincode',
        'pick_address_id',
        'status'
    ];

    public function productsData(): HasMany
    {
        return $this->hasMany(Product::class, 'order_id', 'id');
    }
}
