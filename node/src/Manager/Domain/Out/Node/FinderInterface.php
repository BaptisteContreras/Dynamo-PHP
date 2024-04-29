<?php

namespace App\Manager\Domain\Out\Node;

use App\Manager\Domain\Model\Node;

interface FinderInterface
{
    /**
     * @return array<Node>
     */
    public function findAll(): array;

    public function findSelfEntry(): ?Node;
}
