<?php

namespace App\Models;

use App\Enums\TransactionStatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use SoftDeletes;

    protected $table = "transactions";

    protected $fillable = [
        'amount',
        'is_received',
        'from_user_id',
        'to_user_id',
        'status',
    ];

    protected $casts = [
        'amount' => 'decimal:2', // Garantir que o valor seja tratado como decimal
        'is_received' => 'boolean',
    ];


    /**
     * Relationship 1:N with the origin user (who made the transaction)
     *
     * @return BelongsTo
     */
    public function fromUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

   /**
     * Relationship 1:N with the destination user (who received the transaction)
     *
     * @return BelongsTo
     */
    public function toUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'to_user_id');
    }

    /**
     * @param $value
     * @return TransactionStatusEnum|\Spatie\Enum\Enum
     */
    public function getStatusAttribute($value)
    {
        return TransactionStatusEnum::from($value);
    }

    /**
     * @param $value
     * @return void
     */
    public function setStatusAttribute($value)
    {
        $this->attributes['status'] = $value instanceof TransactionStatusEnum ? $value->value : $value;
    }
}
