<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use HasFactory;
    protected $guarded=[];
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    public function purechases_orders()
    {
        return $this->hasMany(PurchaseOrders::class);
    }
    public function restaurants()
    {
        return $this->belongsToMany(User::class, 'restaurant_supplier');
    }
    public function inventories_items()
    {
        return $this->hasMany(InventoryItem::class);
    }
}
