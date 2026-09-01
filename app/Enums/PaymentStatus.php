<?php

namespace App\Enums;

enum PaymentStatus: string
{
    case Unpaid = 'unpaid';
    case Paid = 'paid';
    case Refunded = 'refunded';
    case Failed = 'failed';

    public function label(): string
    {
        return match ($this) {
            self::Unpaid => 'پرداخت نشده',
            self::Paid => 'پرداخت شده',
            self::Refunded => 'بازگشت وجه',
            self::Failed => 'ناموفق',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::Unpaid => 'bg-ink-100 text-ink-600',
            self::Paid => 'bg-success-50 text-success-600',
            self::Refunded => 'bg-info-50 text-info-600',
            self::Failed => 'bg-brand-50 text-brand-600',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(fn ($c) => [$c->value => $c->label()])->all();
    }
}
