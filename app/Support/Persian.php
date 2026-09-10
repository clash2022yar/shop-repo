<?php

namespace App\Support;

use DateTimeInterface;
use IntlDateFormatter;

class Persian
{
    public static function date(DateTimeInterface $date): string
    {
        return (new IntlDateFormatter('fa_IR@calendar=persian', IntlDateFormatter::MEDIUM, IntlDateFormatter::NONE, 'Asia/Tehran', IntlDateFormatter::TRADITIONAL, 'yyyy/MM/dd'))->format($date);
    }
}
