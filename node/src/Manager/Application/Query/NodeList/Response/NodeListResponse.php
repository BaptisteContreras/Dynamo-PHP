<?php

namespace App\Manager\Application\Query\NodeList\Response;

use App\Manager\Domain\Model\Aggregate\Node\Node;
use App\Shared\Application\ApplicationResponseInterface;

abstract class NodeListResponse implements ApplicationResponseInterface
{
    /**
     * @param array<Node> $nodes
     */
    public static function success(array $nodes): self
    {
        return new SuccessResponse($nodes);
    }

    public static function errorBadFilters(): self
    {
        return new BadFiltersResponse();
    }
}
