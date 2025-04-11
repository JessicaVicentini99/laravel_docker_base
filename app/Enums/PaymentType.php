<?php


namespace App\Enums;


class PaymentType
{
    const PIX = 1;
    const CREDIT_CARD = 2;
    const DEBT_CARD = 3;
    const TRANSFER = 4;

    public static function values()
    {
        return [
            self::PIX,
            self::CREDIT_CARD,
            self::DEBT_CARD,
            self::TRANSFER
        ];
    }
}
