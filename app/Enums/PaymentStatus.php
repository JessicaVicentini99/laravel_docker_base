<?php


namespace App\Enums;


class PaymentStatus
{
    const PENDING = 'pending';
    const PROCESSING = 'processing';
    const COMPLETED = 'completed';
    const CANCELED = 'canceled';
    const FAILED = 'failed';

}
