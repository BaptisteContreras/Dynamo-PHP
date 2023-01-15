<?php

namespace App\Manager\Domain\Contract\Out\Finder;

use App\Manager\Domain\Model\Entity\LabelSlot;

interface LabelFinder
{
    /**
     * Returns all free label slots.
     *
     * /!\ This method does not know anything about assignation and locking. It may return label slots that are locked
     * and in a process of being bound /!\
     *
     * @return array<LabelSlot>
     */
    public function findFree(): array;
}
