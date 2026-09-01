<?php

namespace App\Support;

use DateTimeInterface;
use Illuminate\Support\Carbon;

/**
 * A tiny, dependency-free Gregorian ⇆ Jalali (Shamsi) converter.
 *
 * Digino is a Persian store, so every user-facing date is Shamsi. Rather than
 * pulling in another package we keep the algorithm here — it is stable, well
 * understood and has no external requirements.
 */
final class Jalali
{
    private const MONTHS = [
        1 => 'فروردین', 'اردیبهشت', 'خرداد', 'تیر', 'مرداد', 'شهریور',
        'مهر', 'آبان', 'آذر', 'دی', 'بهمن', 'اسفند',
    ];

    private const WEEKDAYS = [
        'Saturday' => 'شنبه', 'Sunday' => 'یکشنبه', 'Monday' => 'دوشنبه',
        'Tuesday' => 'سه‌شنبه', 'Wednesday' => 'چهارشنبه',
        'Thursday' => 'پنجشنبه', 'Friday' => 'جمعه',
    ];

    /** @return array{0:int,1:int,2:int} [year, month, day] */
    public static function toJalali(int $gy, int $gm, int $gd): array
    {
        $g_d_m = [0, 31, 59, 90, 120, 151, 181, 212, 243, 273, 304, 334];
        $gy2 = ($gm > 2) ? ($gy + 1) : $gy;
        $days = 355666 + (365 * $gy) + intdiv($gy2 + 3, 4) - intdiv($gy2 + 99, 100)
            + intdiv($gy2 + 399, 400) + $gd + $g_d_m[$gm - 1];

        $jy = -1595 + (33 * intdiv($days, 12053));
        $days %= 12053;
        $jy += 4 * intdiv($days, 1461);
        $days %= 1461;

        if ($days > 365) {
            $jy += intdiv($days - 1, 365);
            $days = ($days - 1) % 365;
        }

        if ($days < 186) {
            $jm = 1 + intdiv($days, 31);
            $jd = 1 + ($days % 31);
        } else {
            $jm = 7 + intdiv($days - 186, 30);
            $jd = 1 + (($days - 186) % 30);
        }

        return [$jy, $jm, $jd];
    }

    /** @return array{0:int,1:int,2:int} [year, month, day] */
    public static function toGregorian(int $jy, int $jm, int $jd): array
    {
        $jy += 1595;
        $days = -355668 + (365 * $jy) + (intdiv($jy, 33) * 8) + intdiv(($jy % 33) + 3, 4) + $jd
            + (($jm < 7) ? ($jm - 1) * 31 : (($jm - 7) * 30) + 186);

        $gy = 400 * intdiv($days, 146097);
        $days %= 146097;

        if ($days > 36524) {
            $gy += 100 * intdiv(--$days, 36524);
            $days %= 36524;
            if ($days >= 365) {
                $days++;
            }
        }

        $gy += 4 * intdiv($days, 1461);
        $days %= 1461;

        if ($days > 365) {
            $gy += intdiv($days - 1, 365);
            $days = ($days - 1) % 365;
        }

        $gd = $days + 1;
        $sal_a = [0, 31, (($gy % 4 === 0 && $gy % 100 !== 0) || ($gy % 400 === 0)) ? 29 : 28,
            31, 30, 31, 30, 31, 31, 30, 31, 30, 31];

        for ($gm = 0; $gm < 13 && $gd > $sal_a[$gm]; $gm++) {
            $gd -= $sal_a[$gm];
        }

        return [$gy, $gm, $gd];
    }

    /**
     * Format a date using a small subset of PHP date tokens:
     * Y y m n d j F l H i s
     */
    public static function format(mixed $date, string $format = 'Y/m/d'): string
    {
        if (empty($date)) {
            return '—';
        }

        $carbon = $date instanceof DateTimeInterface ? Carbon::instance($date) : Carbon::parse($date);
        $carbon = $carbon->timezone(config('app.timezone'));

        [$jy, $jm, $jd] = self::toJalali((int) $carbon->format('Y'), (int) $carbon->format('m'), (int) $carbon->format('d'));

        $replacements = [
            'Y' => $jy,
            'y' => substr((string) $jy, 2),
            'm' => str_pad((string) $jm, 2, '0', STR_PAD_LEFT),
            'n' => $jm,
            'd' => str_pad((string) $jd, 2, '0', STR_PAD_LEFT),
            'j' => $jd,
            'F' => self::MONTHS[$jm],
            'l' => self::WEEKDAYS[$carbon->format('l')] ?? '',
            'H' => $carbon->format('H'),
            'i' => $carbon->format('i'),
            's' => $carbon->format('s'),
        ];

        $out = '';
        foreach (str_split($format) as $char) {
            $out .= $replacements[$char] ?? $char;
        }

        return fa_number($out);
    }

    /** "۳ روز پیش" style relative time in Persian. */
    public static function human(mixed $date): string
    {
        if (empty($date)) {
            return '—';
        }

        $carbon = $date instanceof DateTimeInterface ? Carbon::instance($date) : Carbon::parse($date);
        $diff = $carbon->diffInSeconds(now(), true);

        return match (true) {
            $diff < 60 => 'لحظاتی پیش',
            $diff < 3600 => fa_number((int) ($diff / 60)).' دقیقه پیش',
            $diff < 86400 => fa_number((int) ($diff / 3600)).' ساعت پیش',
            $diff < 2592000 => fa_number((int) ($diff / 86400)).' روز پیش',
            $diff < 31536000 => fa_number((int) ($diff / 2592000)).' ماه پیش',
            default => fa_number((int) ($diff / 31536000)).' سال پیش',
        };
    }
}
