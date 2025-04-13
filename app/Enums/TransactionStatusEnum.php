<?php


namespace App\Enums;

use phpDocumentor\Reflection\Types\Boolean;
use \Spatie\Enum\Enum;

/**
 * @method static self pending()
 * @method static self processing()
 * @method static self completed()
 * @method static self canceled()
 * @method static self failed()
 */
class TransactionStatusEnum extends Enum
{
    const MAP_INDEX = [
        'pending' => 1,
        'processing' => 2,
        'completed' => 3,
        'canceled' => 4,
        'failed' => 5,
    ];

    const MAP_VALUE = [
        'pending' => 'pending',
        'processing' => 'processing',
        'completed' => 'completed',
        'canceled' => 'canceled',
        'failed' => 'failed',
    ];
}
