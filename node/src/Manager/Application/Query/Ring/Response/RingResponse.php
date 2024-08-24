<?php

namespace App\Manager\Application\Query\Ring\Response;

use App\Manager\Domain\Model\Aggregate\Ring\Ring;
use App\Shared\Application\ApplicationResponseInterface;

abstract class RingResponse implements ApplicationResponseInterface
{
    public static function success(Ring $ring): self
    {
        return new SuccessResponse($ring);
    }
}
