<?php

namespace App\Manager\Domain\Contract\Out\Finder;

use App\Manager\Domain\Model\Dto\Label;

interface LabelFinder
{
    /**
     * Returns all free label slots.
     *
     * /!\ This method does not know anything about assignation and locking. It may return label slots that are locked
     * and in a process of being bound /!\
     *
     * @return array<Label>
     */
    public function findFree(): array;
}
