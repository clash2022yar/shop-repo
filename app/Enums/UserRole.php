<?php

namespace App\Enums;

enum UserRole: string
{
    case Customer = 'customer';
    case Manager = 'manager';
    case Admin = 'admin';

    public function label(): string
    {
        return match ($this) {
            self::Customer => 'مشتری',
            self::Manager => 'مدیر بخش',
            self::Admin => 'مدیر کل',
        };
    }

    public function canEnterAdmin(): bool
    {
        return $this !== self::Customer;
    }

    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(fn ($c) => [$c->value => $c->label()])->all();
    }
}
