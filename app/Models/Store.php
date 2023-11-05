<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Store extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];
    protected $dates = ['deleted_at'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'store_users');
    }

    public function branches()
    {
        return $this->hasMany(Branch::class);
    }

    public function zones()
    {
        return $this->hasMany(Zone::class);
    }

    public function customers()
    {
        return $this->belongsToMany(Customer::class, 'store_customers', 'store_id', 'customer_id');
    }
}
