<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Zone extends Model
{
    use HasFactory;
    protected $guarded=[];
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
    public function tables()
    {
        return $this->hasMany(Table::class);
    }
}
