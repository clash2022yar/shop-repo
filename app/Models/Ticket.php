<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ticket extends Model
{
    protected $fillable = ['code', 'user_id', 'order_id', 'subject', 'department', 'priority', 'status'];

    protected static function booted(): void
    {
        static::creating(fn (self $t) => $t->code ??= 'TK'.now()->format('ym').random_int(100, 999));
    }

    public function getRouteKeyName(): string
    {
        return 'code';
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(TicketMessage::class)->oldest();
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'open' => 'در انتظار پاسخ',
            'answered' => 'پاسخ داده شده',
            'closed' => 'بسته شده',
            default => $this->status,
        };
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'open' => 'bg-warning-50 text-warning-600',
            'answered' => 'bg-success-50 text-success-600',
            'closed' => 'bg-ink-100 text-ink-600',
            default => 'bg-ink-100 text-ink-600',
        };
    }

    public function getPriorityLabelAttribute(): string
    {
        return match ($this->priority) {
            'low' => 'کم', 'normal' => 'عادی', 'high' => 'زیاد', default => $this->priority,
        };
    }

    public function getDepartmentLabelAttribute(): string
    {
        return match ($this->department) {
            'support' => 'پشتیبانی', 'orders' => 'سفارش‌ها',
            'technical' => 'فنی', 'finance' => 'مالی', default => $this->department,
        };
    }
}
