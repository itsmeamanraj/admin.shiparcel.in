<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'website_url',
        'billing_address',
        'zipcode',
        'city',
        'state',
        'image_url',
        'status',
        'chargeable_amount',
        'cod_charges',
        'cod_percentage'

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


    public function courierCompanies()
    {
        return $this->belongsToMany(CourierCompany::class, 'user_courier_weight_slabs', 'user_id', 'courier_company_id');
    }

    // User's selected weight slabs per courier company
    public function courierWeightSlabs()
    {
        return $this->hasMany(UserCourierWeightSlab::class, 'user_id');
    }
}
