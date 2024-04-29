<?php

namespace App\Manager\Domain\Contract\Out\Repository;

use App\Manager\Domain\Model\Entity\LabelSlot;

interface LabelSlotRepositoryInterface
{
    /**
     * Add the creation of the label to the current transaction.
     * If $flush = true, commit the transaction.
     */
    public function add(LabelSlot $label, bool $flush): void;

    /**
     * Add the creation of the labels to the current transaction.
     * If $flush = true, commit the transaction.
     *
     * @param array<LabelSlot> $labels
     */
    public function bulkAdd(array $labels, bool $flush): void;

    /**
     * Add the removal of the label to the current transaction.
     * If $flush = true, commit the transaction.
     */
    public function remove(LabelSlot $label, bool $flush): void;

    /**
     * Add the update of the label to the current transaction.
     * If $flush = true, commit the transaction.
     */
    public function update(LabelSlot $label, bool $flush): void;

    /**
     * Commit the transaction.
     */
    public function flushTransaction(): void;
}
