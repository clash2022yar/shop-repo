<?php

use App\Support\Jalali;
use Illuminate\Support\Facades\Cache;

if (! function_exists('digino')) {
    /**
     * Read a runtime store setting (Admin » Settings) with a config fallback.
     */
    function digino(string $key, mixed $default = null): mixed
    {
        return App\Models\Setting::get($key, $default);
    }
}

if (! function_exists('toman')) {
    /**
     * Format an integer amount of Toman with thousand separators.
     */
    function toman(int|float|null $amount, bool $withLabel = false): string
    {
        $formatted = number_format((float) ($amount ?? 0));

        return $withLabel ? $formatted.' '.config('digino.currency.label') : $formatted;
    }
}

if (! function_exists('fa_number')) {
    /**
     * Convert Latin digits in a string to Persian digits.
     */
    function fa_number(string|int|float|null $value): string
    {
        return strtr((string) $value, [
            '0' => '۰', '1' => '۱', '2' => '۲', '3' => '۳', '4' => '۴',
            '5' => '۵', '6' => '۶', '7' => '۷', '8' => '۸', '9' => '۹',
        ]);
    }
}

if (! function_exists('en_number')) {
    /**
     * Normalise Persian/Arabic digits back to Latin digits (for form input).
     */
    function en_number(string|null $value): string
    {
        return strtr((string) $value, [
            '۰' => '0', '۱' => '1', '۲' => '2', '۳' => '3', '۴' => '4',
            '۵' => '5', '۶' => '6', '۷' => '7', '۸' => '8', '۹' => '9',
            '٠' => '0', '١' => '1', '٢' => '2', '٣' => '3', '٤' => '4',
            '٥' => '5', '٦' => '6', '٧' => '7', '٨' => '8', '٩' => '9',
        ]);
    }
}

if (! function_exists('jalali')) {
    /**
     * Format a date as a Jalali (Shamsi) string, e.g. 1403/03/25.
     */
    function jalali(mixed $date, string $format = 'Y/m/d'): string
    {
        return Jalali::format($date, $format);
    }
}

if (! function_exists('jalali_human')) {
    function jalali_human(mixed $date): string
    {
        return Jalali::human($date);
    }
}

if (! function_exists('svg_icon')) {
    /**
     * Render one of the project's inline SVG icons.
     * Icons live in resources/views/components/icon.blade.php.
     */
    function svg_icon(string $name, string $class = 'h-5 w-5'): Illuminate\Contracts\Support\Htmlable
    {
        return new Illuminate\Support\HtmlString(
            view('components.icon', ['name' => $name, 'class' => $class])->render()
        );
    }
}

if (! function_exists('cache_remember_short')) {
    function cache_remember_short(string $key, Closure $callback, int $seconds = 300): mixed
    {
        return app()->isLocal() ? $callback() : Cache::remember($key, $seconds, $callback);
    }
}
