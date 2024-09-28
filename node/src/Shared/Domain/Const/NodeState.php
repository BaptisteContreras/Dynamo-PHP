<?php

namespace App\Shared\Domain\Const;

enum NodeState: int
{
    case ERROR = 0;
    case UP = 1;
}
