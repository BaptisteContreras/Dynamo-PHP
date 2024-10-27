<?php

namespace App\Foreground\Domain\Model\Const;

enum ForwardResult: int
{
    case SUCCESS = 0;
    case TIMEOUT = 1;
    case TECHNICAL_ERROR = 2;
}
