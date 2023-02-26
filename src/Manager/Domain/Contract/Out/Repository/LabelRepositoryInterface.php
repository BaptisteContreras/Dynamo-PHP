<?php

namespace App\Manager\Domain\Contract\Out\Repository;

use App\Manager\Domain\Model\Entity\LabelSlot;

interface LabelRepositoryInterface
{
    public function add(LabelSlot $label, bool $flush): void;

    /**
     * @param array<LabelSlot> $labels
     */
    public function bulkAdd(array $labels): void;

    public function remove(LabelSlot $label, bool $flush): void;

    public function update(LabelSlot $label, bool $flush): void;
}
