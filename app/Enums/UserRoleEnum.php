<?php


namespace App\Enums;

use phpDocumentor\Reflection\Types\Boolean;
use \Spatie\Enum\Enum;

/**
 * @method static self customer()
 * @method static self merchant()
 *
 * @method static boolean isCustomer(int|string $value = null)
 * @method static boolean isMerchant(int|string $value = null)
 */
class UserRoleEnum extends Enum
{
    const MAP_INDEX = [
        'customer' => 1,
        'merchant' => 2,
    ];

    const MAP_VALUE = [
        'customer' => 'customer',
        'merchant' => 'merchant'
    ];
}
