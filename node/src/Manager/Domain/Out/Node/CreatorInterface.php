<?php

namespace App\Manager\Domain\Out\Node;

use App\Manager\Domain\Model\Node;

interface CreatorInterface
{
    public function createSelfNode(string $networkAddress, int $networkPort, int $weight, \DateTimeImmutable $joinedAt): void;

    public function saveNode(Node $node): void;
}
