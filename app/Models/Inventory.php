<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inventory extends Model
{
    use HasFactory;
    protected $guarded=[];
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    public function resturant()
    {
        return $this->belongsTo(User::class);
    }
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
    public function inventoryItems()
    {
        return $this->hasMany(InventoryItem::class);
    }
}
