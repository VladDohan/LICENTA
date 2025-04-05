<?php

namespace App\Enum;

enum AssetType: string
{
    case CRYPTO = 'crypto';
    case STOCK = 'stock';
    case ETF = 'etf';
    case COMMODITY = 'commodity';
}