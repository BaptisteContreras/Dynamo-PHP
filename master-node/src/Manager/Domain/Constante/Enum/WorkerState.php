<?php

namespace App\Manager\Domain\Constante\Enum;

enum WorkerState: string
{
    case JOINING = 'joining';
    case UP = 'up';
    case LEAVING = 'leaving';
    case ERROR = 'on_error';
    case JOINING_ERROR = 'joining_error';
    case RECOVERING = 'recovering';
}
