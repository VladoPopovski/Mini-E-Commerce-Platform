<?php

namespace App\Enums;

enum PaymentMethod: string
{
    case CREDIT_CARD   = 'credit_card';
    case WALLET        = 'wallet';
    case BANK_TRANSFER = 'bank_transfer';
}
