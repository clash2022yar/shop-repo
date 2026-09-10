<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['number', 'checkout_token', 'status', 'subtotal', 'discount', 'shipping', 'total', 'address', 'payment_method', 'coupon_id', 'note'])]
class Order extends Model
{
    protected function casts(): array
    {
        return ['address' => 'array'];
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getStatusLabelAttribute()
    {
        return ['processing' => 'در حال پردازش', 'shipped' => 'ارسال شده', 'delivered' => 'تحویل شده', 'cancelled' => 'لغو شده'][$this->status] ?? $this->status;
    }
}
