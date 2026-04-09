<?php

namespace App\Enums;

enum OrderStatus: string
{
    case PENDING   = 'pending';
    case PAID      = 'paid';
    case SHIPPED   = 'shipped';
    case DELIVERED = 'delivered';

    public function canTransitionTo(self $target): bool
    {
        $sequence = [self::PENDING, self::PAID, self::SHIPPED, self::DELIVERED];

        return array_search($target, $sequence, true) > array_search($this, $sequence, true);
    }
}
