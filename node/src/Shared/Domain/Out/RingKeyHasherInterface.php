<?php

namespace App\Shared\Domain\Out;

interface RingKeyHasherInterface
{
    /**
     * @return int<0, 360>
     */
    public function hash(string $key): int;
}
