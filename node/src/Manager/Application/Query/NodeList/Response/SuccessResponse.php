<?php

namespace App\Manager\Application\Query\NodeList\Response;

use App\Manager\Domain\Model\Aggregate\Node\Node;

final class SuccessResponse extends NodeListResponse
{
    /**
     * @param array<Node> $nodes
     */
    public function __construct(private array $nodes)
    {
    }

    /**
     * @return array<Node>
     */
    public function getNodes(): array
    {
        return $this->nodes;
    }
}
