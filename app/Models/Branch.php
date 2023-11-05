<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Branch extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];
    protected $dates = ['deleted_at'];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }
    public function inventory()
    {
        return $this->hasOne(Inventory::class);
    }
    public function inventoryItems()
    {
        return $this->belongsToMany(InventoryItem::class, 'inventory_item_branch', 'branch_id', 'inventory_item_id');
    }
    public function categories()
    {
        return $this->hasMany(Category::class);
    }
    public function zones()
    {
        return $this->hasMany(Zone::class);
    }
    public function purchase_orders()
    {
        return $this->hasMany(PurchaseOrders::class);
    }
}
