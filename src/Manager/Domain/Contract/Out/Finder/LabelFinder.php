<?php

namespace App\Manager\Domain\Contract\Out\Finder;

use App\Manager\Domain\Model\Entity\LabelSlot;

interface LabelFinder
{
    /**
     * Returns all free labels slots.
     *
     * /!\ This method does not know anything about assignation and locking. It may return label slots that are locked
     * and in a process of being bound /!\
     *
     * @return array<LabelSlot>
     */
    public function findFree(): array;

    /**
     * Return all labels slots.
     *
     * This method should be named findAll, but it conflicts with the doctrine repo findAll method signature, so I had
     * to find a workaround...
     *
     * @return array<LabelSlot>
     */
    public function findAllSlots(): array;
}
