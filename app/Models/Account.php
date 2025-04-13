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

    /**
     * @var array<string, string>
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return string
     */
    public function scopeFindByUserId($query, $userId)
    {
        return $query->where('user_id', $userId)
            ->whereNull('deleted_at')
            ->first();
    }


    /**
     * Defining a mutator to ensure the balance is always positive (optional)
     * @param float $value
     */
    public function setBalanceAttribute($value)
    {
        $this->attributes['balance'] = max(0, $value); // Garantir que o saldo não seja negativo
    }

}
