<?php

namespace Tests\Unit;

use App\Support\Persian;
use PHPUnit\Framework\TestCase;

class ExampleTest extends TestCase
{
    public function test_dates_use_the_persian_calendar(): void
    {
        $this->assertSame('۱۴۰۵/۰۶/۱۹', Persian::date(new \DateTimeImmutable('2026-09-10T10:00:00Z')));
    }
}
