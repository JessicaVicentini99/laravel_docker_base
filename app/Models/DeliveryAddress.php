<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DeliveryAddress extends Model
{
    use SoftDeletes;
    protected $table = 'delivery_addresses';
    protected $fillable = [
        'purchase_id',
        'number',
        'address',
        'district',
        'city',
        'state'
    ];
}
