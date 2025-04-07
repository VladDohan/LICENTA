<?php

namespace App\Enum;

enum TransactionType: string
{
    case BUY = 'buy';
    case SELL = 'sell';
}