<?php

namespace App\Shared\Domain\Const;

enum MembershipState: int
{
    case JOINING = 0;
    case UP = 1;
    case LEAVING = 2;
    case ERROR = 3;
    case JOINING_ERROR = 4;
    case RECOVERING = 5;
}
