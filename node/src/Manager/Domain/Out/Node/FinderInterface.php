<?php

namespace App\Manager\Domain\Out\Node;

use App\Manager\Domain\Model\Aggregate\Node\Node;

interface FinderInterface
{
    /**
     * @return array<Node>
     */
    public function getAll(): array;

    public function findSelfEntry(): ?Node;
}
