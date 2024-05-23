<?php

namespace App\Shared\Domain\Const;

enum HistoryEventType: int
{
    case JOIN = 0;
    case LEAVE = 1;
}
