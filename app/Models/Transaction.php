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
     * @return BelongsTo
     */
    public function fromUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    // Relacionamento 1:N com o usuário de destino (quem recebeu a transação)
    public function toUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'to_user_id');
    }


    public function getStatusAttribute($value)
    {
        return TransactionStatusEnum::from($value);
    }

    // Mutador para garantir que o status seja salvo corretamente
    public function setStatusAttribute($value)
    {
        $this->attributes['status'] = $value instanceof TransactionStatusEnum ? $value->value : $value;
    }
}
