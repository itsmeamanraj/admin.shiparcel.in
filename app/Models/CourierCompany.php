<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourierCompany extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'status'];

    public function userSelections()
    {
        return $this->hasMany(UserCourierWeightSlab::class);
    }
    
}
