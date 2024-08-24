<?php

namespace App\Manager\Application\Query\Ring\Response;

use App\Manager\Domain\Model\Aggregate\Node\Node;
use App\Shared\Application\ApplicationResponseInterface;

abstract class RingResponse implements ApplicationResponseInterface
{
    public static function success(Node $node): self
    {
        return new SuccessResponse($node);
    }
}
