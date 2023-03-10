<?php

namespace App\Manager\Domain\Contract\Out\Counter;

interface LabelCounterInterface
{
    /**
     * Returns the number of label slots.
     */
    public function countAll(): int;

    /**
     * Returns the number of free label slots.
     */
    public function countFree(): int;
}
