<?php

namespace App\Models;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    /** @use HasFactory<\Database\Factories\OrderFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code', 'user_id', 'status', 'payment_status', 'payment_method', 'transaction_ref',
        'paid_at', 'items_total', 'discount_total', 'coupon_discount', 'shipping_cost',
        'tax_total', 'grand_total', 'coupon_id', 'shipping_method_id', 'receiver_name',
        'receiver_mobile', 'province', 'city', 'address_line', 'postal_code',
        'tracking_code', 'customer_note', 'admin_note', 'shipped_at', 'delivered_at', 'cancelled_at',
    ];

    protected function casts(): array
    {
        return [
            'status' => OrderStatus::class,
            'payment_status' => PaymentStatus::class,
            'paid_at' => 'datetime',
            'shipped_at' => 'datetime',
            'delivered_at' => 'datetime',
            'cancelled_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (self $order) {
            $order->code ??= static::generateCode();
        });
    }

    public static function generateCode(): string
    {
        do {
            $code = 'DG'.now()->format('ym').random_int(1000, 9999);
        } while (static::withTrashed()->where('code', $code)->exists());

        return $code;
    }

    public function getRouteKeyName(): string
    {
        return 'code';
    }

    // ---------------------------------------------------------- relations
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }

    public function shippingMethod(): BelongsTo
    {
        return $this->belongsTo(ShippingMethod::class);
    }

    public function statusLogs(): HasMany
    {
        return $this->hasMany(OrderStatusLog::class)->latest();
    }

    // ------------------------------------------------------------ scopes
    public function scopeStatus($q, ?string $status)
    {
        return $status ? $q->where('status', $status) : $q;
    }

    public function scopeSearch($q, ?string $term)
    {
        if (blank($term)) {
            return $q;
        }

        $term = en_number($term);

        return $q->where(fn ($s) => $s->where('code', 'like', "%{$term}%")
            ->orWhere('receiver_name', 'like', "%{$term}%")
            ->orWhere('receiver_mobile', 'like', "%{$term}%")
            ->orWhere('tracking_code', 'like', "%{$term}%"));
    }

    public function scopePaid($q)
    {
        return $q->where('payment_status', PaymentStatus::Paid->value);
    }

    // --------------------------------------------------------- accessors
    public function getItemsCountAttribute(): int
    {
        return (int) $this->items->sum('quantity');
    }

    public function getFullAddressAttribute(): string
    {
        return collect([$this->province, $this->city, $this->address_line])->filter()->implode('، ');
    }

    public function getIsCancellableAttribute(): bool
    {
        return in_array($this->status, [OrderStatus::Pending, OrderStatus::Paid, OrderStatus::Processing], true);
    }

    public function getIsPayableAttribute(): bool
    {
        return $this->payment_status === PaymentStatus::Unpaid && $this->status === OrderStatus::Pending;
    }

    /** Move the order to a new status and write an audit log entry. */
    public function transitionTo(OrderStatus $status, ?string $note = null, ?int $actorId = null): bool
    {
        $from = $this->status;

        if ($from === $status) {
            return false;
        }

        $this->status = $status;

        match ($status) {
            OrderStatus::Shipped => $this->shipped_at = now(),
            OrderStatus::Delivered => $this->delivered_at = now(),
            OrderStatus::Cancelled => $this->cancelled_at = now(),
            OrderStatus::Paid => [$this->paid_at = now(), $this->payment_status = PaymentStatus::Paid],
            default => null,
        };

        $this->save();

        $this->statusLogs()->create([
            'user_id' => $actorId,
            'from_status' => $from->value,
            'to_status' => $status->value,
            'note' => $note,
        ]);

        return true;
    }
}
