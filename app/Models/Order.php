<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
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
}
