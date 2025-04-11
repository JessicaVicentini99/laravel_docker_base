<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Purchase extends Model
{
    use SoftDeletes;

    protected $table = "purchases";

    protected $fillable = [
      'user_id',
      'payment_type',
      'payment_status'
    ];

    public function items(){
        return $this->hasMany(PurchaseItem::class, 'purchase_id');
    }
    public function delivery_address(){
        return $this->hasOne(DeliveryAddress::class, 'purchase_id');
    }

}
