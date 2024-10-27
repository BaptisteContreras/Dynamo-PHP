<?php

namespace App\Foreground\Domain\Out\Node;

use App\Foreground\Domain\Model\Aggregate\Node\Node;
use Symfony\Component\Uid\UuidV7;

interface FinderInterface
{
    public function getLocalEntry(): Node;

    /**
     * @param array<UuidV7> $ids
     *
     * @return array<string, Node>
     */
    public function findByIds(array $ids): array;
}
