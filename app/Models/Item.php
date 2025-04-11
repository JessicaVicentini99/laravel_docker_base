<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use SoftDeletes;

    protected $table='items';
    protected $fillable = ['name', 'description', 'price', 'item_type_id'];

    public function type(){
        return $this->belongsTo(ItemType::class,'item_type_id','id');
    }
}
