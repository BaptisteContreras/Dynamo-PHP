<?php

namespace App\Manager\Domain\Model\Dto;

class LabelsAssignation
{
    /**
     * @param array<Label> $subLabels
     */
    public function __construct(
        private readonly string $labelName,
        private readonly array $subLabels
    ) {
    }

    public function getLabelName(): string
    {
        return $this->labelName;
    }

    public function getSubLabels(): array
    {
        return $this->subLabels;
    }
}
