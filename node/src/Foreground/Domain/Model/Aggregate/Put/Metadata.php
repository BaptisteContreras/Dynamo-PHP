<?php

namespace App\Foreground\Domain\Model\Aggregate\Put;

final class Metadata
{
    /**
     * @param int<0, 360> $ringKey
     */
    public function __construct(
        private string $version,
        private readonly int $ringKey,
    ) {
    }

    public function getVersion(): string
    {
        return $this->version;
    }

    /**
     * @return int<0, 360>
     */
    public function getRingKey(): int
    {
        return $this->ringKey;
    }
}
