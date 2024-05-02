<?php

namespace App\Manager\Domain\Out\Node;

use App\Manager\Domain\Model\Node;

interface CreatorInterface
{
    public function createSelfNode(string $networkAddress, int $networkPort, int $weight, bool $isSeed, \DateTimeImmutable $joinedAt): void;

    public function saveNode(Node $node): void;
}
