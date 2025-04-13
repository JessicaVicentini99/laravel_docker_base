<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Account extends Model
{
    use SoftDeletes;

    protected $table = "accounts";

    protected $fillable = [
        'agency',
        'number',
        'balance',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeFindByUserId($query, $userId)
    {
        return $query->where('user_id', $userId)
            ->whereNull('deleted_at')
            ->first();
    }

    // Definindo um mutador para garantir que o saldo seja sempre positivo (opcional)
    public function setBalanceAttribute($value)
    {
        $this->attributes['balance'] = max(0, $value); // Garantir que o saldo não seja negativo
    }

}
