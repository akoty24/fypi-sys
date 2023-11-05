<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Table extends Model
{
    use HasFactory,SoftDeletes;
    protected $guarded=[];
    protected $dates = ['deleted_at'];

    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }

}
