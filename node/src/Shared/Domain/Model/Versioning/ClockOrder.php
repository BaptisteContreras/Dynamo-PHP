<?php

namespace App\Shared\Domain\Model\Versioning;

enum ClockOrder: string
{
    case IDENTICAL = 'identical';
    case HAPPEN_BEFORE = 'before';
    case HAPPEN_AFTER = 'after';
    case CONCURRENT = 'concurrent';
}
