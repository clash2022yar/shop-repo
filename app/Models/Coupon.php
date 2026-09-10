<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['code', 'percent', 'min_total', 'usage_limit', 'used', 'expires_at', 'active'])]
class Coupon extends Model
{
    protected function casts(): array
    {
        return ['expires_at' => 'date', 'active' => 'boolean'];
    }
}
