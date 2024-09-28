<?php

namespace App\Manager\Application\Query\NodeList\Request;

use App\Shared\Domain\Const\MembershipState;

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
     * @return array<MembershipState>|null
     *
     * @throws \ValueError
     */
    public function getState(): ?array
    {
        return $this->state ? array_map(fn (int $rawState) => MembershipState::from($rawState), $this->state) : null;
    }
}
