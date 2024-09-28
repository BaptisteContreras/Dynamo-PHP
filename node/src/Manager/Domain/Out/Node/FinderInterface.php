<?php

namespace App\Manager\Domain\Out\Node;

use App\Manager\Domain\Model\Aggregate\Node\Node;
use App\Shared\Domain\Const\MembershipState;

interface FinderInterface
{
    /**
     * @return array<Node>
     */
    public function getAll(): array;

    public function findSelfEntry(): ?Node;

    /**
     * @param array<MembershipState>|null $states
     *
     * @return array<Node>
     */
    public function searchBy(?bool $seed = null, ?array $states = null): array;
}
