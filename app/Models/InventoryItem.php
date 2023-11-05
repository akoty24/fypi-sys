<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InventoryItem extends Model
{
    use HasFactory;
    protected $guarded=[];
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    public function inventory()
    {
        return $this->belongsTo(Inventory::class);
    }
    public function categories()
    {
        return $this->hasMany(Category::class);
    }
    public function branches()
    {
        return $this->belongsToMany(Branch::class, 'inventory_item_branch', 'inventory_item_id', 'branch_id');
    }
    public function products()
    {
        return $this->hasMany(Product::class);
    }
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}
