<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory;
    protected $guarded=[];
    use SoftDeletes;

    // Specify the columns that should be treated as dates
    protected $dates = ['deleted_at'];
    public function products()
    {
        return $this->hasMany(Product::class);
    }
    public function resturant()
    {
        return $this->belongsTo(User::class);
    }
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function inventory()
    {
        return $this->belongsTo(Inventory::class);
    }
}
