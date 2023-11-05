<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory;
    protected $guarded=[];
    use SoftDeletes;

    // Specify the columns that should be treated as dates
    protected $dates = ['deleted_at'];
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function inventory()
    {
        return $this->belongsTo(Inventory::class);
    }
}
