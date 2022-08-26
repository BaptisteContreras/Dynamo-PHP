<?php

namespace App\Manager\Domain\Constante\Enum;

enum WorkerState: string
{
    case JOINING = 'joining';
    case UP = 'up';
    case LEAVING = 'leaving';
    case LEFT = 'left';
    case ON_ERROR = 'on_error';
    case RECOVERING = 'recovering';
}
