<?php

namespace App\Shared\Domain\Const;

enum HistoryEventType: int
{
    case CHANGE_MEMBERSHIP = 0;
    case CHANGE_HOST = 1;
    case CHANGE_NETWORK_PORT = 2;
    case CHANGE_WEIGHT = 3;
    case CHANGE_SEED = 4;
}
