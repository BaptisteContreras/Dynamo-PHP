<?php

namespace App\Manager\Application\Query\NodeList\Request;

use App\Shared\Domain\Const\NodeState;

class FilteredRequest extends NodeListRequest
{
    /**
     * @param array<int>|null $state
     */
    public function __construct(
        private readonly ?bool $seed,
        private readonly ?array $state,
    ) {
    }

    public function getSeed(): ?bool
    {
        return $this->seed;
    }

    /**
     * @return array<NodeState>|null
     *
     * @throws \ValueError
     */
    public function getState(): ?array
    {
        return $this->state ? array_map(fn (int $rawState) => NodeState::from($rawState), $this->state) : null;
    }
}
