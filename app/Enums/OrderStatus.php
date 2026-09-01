<?php

namespace App\Enums;

enum OrderStatus: string
{
    case Pending = 'pending';
    case Paid = 'paid';
    case Processing = 'processing';
    case Shipped = 'shipped';
    case Delivered = 'delivered';
    case Cancelled = 'cancelled';
    case Returned = 'returned';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'در انتظار پرداخت',
            self::Paid => 'پرداخت شده',
            self::Processing => 'در حال پردازش',
            self::Shipped => 'ارسال شده',
            self::Delivered => 'تحویل شده',
            self::Cancelled => 'لغو شده',
            self::Returned => 'مرجوع شده',
        };
    }

    /** Tailwind classes for the status pill. */
    public function badgeClass(): string
    {
        return match ($this) {
            self::Pending => 'bg-warning-50 text-warning-600',
            self::Paid => 'bg-info-50 text-info-600',
            self::Processing => 'bg-warning-50 text-warning-600',
            self::Shipped => 'bg-success-50 text-success-600',
            self::Delivered => 'bg-success-50 text-success-600',
            self::Cancelled => 'bg-brand-50 text-brand-600',
            self::Returned => 'bg-ink-100 text-ink-600',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::Pending => 'clock',
            self::Paid => 'credit-card',
            self::Processing => 'box',
            self::Shipped => 'truck',
            self::Delivered => 'check-circle',
            self::Cancelled => 'x-circle',
            self::Returned => 'rotate-left',
        };
    }

    /** Statuses an order may legally move to next. */
    public function allowedTransitions(): array
    {
        return match ($this) {
            self::Pending => [self::Paid, self::Cancelled],
            self::Paid => [self::Processing, self::Cancelled],
            self::Processing => [self::Shipped, self::Cancelled],
            self::Shipped => [self::Delivered, self::Returned],
            self::Delivered => [self::Returned],
            self::Cancelled, self::Returned => [],
        };
    }

    public function isOpen(): bool
    {
        return ! in_array($this, [self::Delivered, self::Cancelled, self::Returned], true);
    }

    /** Progress step (1-4) used by the order tracking timeline. */
    public function step(): int
    {
        return match ($this) {
            self::Pending => 1,
            self::Paid, self::Processing => 2,
            self::Shipped => 3,
            self::Delivered => 4,
            self::Cancelled, self::Returned => 0,
        };
    }

    /** @return array<string,string> */
    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(fn ($c) => [$c->value => $c->label()])->all();
    }
}
