<?php

namespace App\Shared\Domain\Const;

enum HistoryEventType: int
{
    case JOIN = 0;
    case LEAVE = 1;
    case CHANGE_HOST = 2;
    case CHANGE_NETWORK_PORT = 3;
    case CHANGE_WEIGHT = 4;
    case CHANGE_SEED = 5;
}
