<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSurfaceCourierRate extends Model
{
    protected $fillable = [
        'user_id',
        'courier_company_id',
        'courier_weight_slab_id',
        'mode',
        'zone',
        'forward_fwd',
        'additional_fwd',
        'forward_rto',
        'additional_rto',
    ];
}
