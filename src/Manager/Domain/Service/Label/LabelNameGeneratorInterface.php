<?php

namespace App\Manager\Domain\Service\Label;

interface LabelNameGeneratorInterface
{
    /**
     * Generate a label name concurrently (i.e. it ensures that a name is unique and never used ever).
     */
    public function generate(): string;
}
