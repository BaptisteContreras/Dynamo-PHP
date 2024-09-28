<?php

namespace App\Shared\Domain\Const;

enum MembershipState: int
{
    case JOINED = 0;
    case LEFT = 1;
}
