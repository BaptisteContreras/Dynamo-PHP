<?php

namespace App\Foreground\Application\Command\Public\Put\Response;

use App\Shared\Application\ApplicationResponseInterface;

abstract class PutDataResponse implements ApplicationResponseInterface
{
    public static function success(): self
    {
        return new SuccessResponse();
    }
}
