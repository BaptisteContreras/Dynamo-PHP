<?php

namespace App\Manager\Domain\Model;

use Symfony\Component\Uid\UuidV7;

final class VirtualNode
{
    public function __construct(
        private readonly UuidV7 $id,
        private readonly \DateTimeImmutable $createdAt,
        private readonly string $label
    ) {
    }
}
