<?php

namespace App\Manager\Domain\Out\Node;

use App\Manager\Domain\Model\Node;

interface CreatorInterface
{
    /**
     * @param positive-int $networkPort
     * @param positive-int $weight
     */
    public function createSelfNode(string $networkAddress, int $networkPort, int $weight, bool $isSeed, string $label, \DateTimeImmutable $joinedAt): Node;

    public function saveNode(Node $node): void;
}
