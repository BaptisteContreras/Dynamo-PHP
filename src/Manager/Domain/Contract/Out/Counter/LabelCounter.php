<?php

namespace App\Manager\Domain\Contract\Out\Counter;

interface LabelCounter
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
