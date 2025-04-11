<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItemType extends Model
{
    use SoftDeletes;

    protected $table = "item_types";
    protected $fillable = [
      "name",
      "description",
    ];

    public function items(){
        return $this->hasMany(Item::class,'item_type_id','id');
    }

}
