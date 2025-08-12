<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PincodeCourier extends Model
{
    protected $table = 'pincode_couriers'; // Your table name

    protected $fillable = [
        'pincode',
        'courier_id',
    ];

    public $timestamps = false; // Set true if you have created_at and updated_at columns
}
