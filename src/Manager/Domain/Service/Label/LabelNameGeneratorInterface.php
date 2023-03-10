<?php

namespace App\Manager\Domain\Service\Label;

use App\Manager\Domain\Exception\LabelNameGeneratorSequenceInitException;
use App\Manager\Domain\Exception\LabelNameGeneratorSequenceInitFailedException;

interface LabelNameGeneratorInterface
{
    /**
     * Generate a label name concurrently (i.e. it ensures that a name is unique and never used ever).
     *
     * @throws LabelNameGeneratorSequenceInitException
     */
    public function generate(): string;

    /**
     * Prepare the underlying storage to store the last label name generated.
     *
     * /!\ Must be called before generate /!\
     *
     * @throws LabelNameGeneratorSequenceInitFailedException
     */
    public function initSequence(): void;
}
