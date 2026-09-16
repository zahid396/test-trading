<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['method', 'number', 'account_type', 'instructions', 'qr_image', 'logo', 'is_active'])]
class PaymentSetting extends Model
{
    /**
     * Get a payment setting by method name.
     */
    public static function getMethod(string $method): ?static
    {
        return static::where('method', $method)->where('is_active', true)->first();
    }
}
