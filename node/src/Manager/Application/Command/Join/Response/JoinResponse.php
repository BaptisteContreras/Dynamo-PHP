<?php

namespace App\Manager\Application\Command\Join\Response;

use App\Manager\Domain\Model\Node;
use App\Shared\Application\ApplicationResponseInterface;

abstract class JoinResponse implements ApplicationResponseInterface
{
    public static function success(Node $node): self
    {
        return new SuccessResponse($node);
    }
}
