<?php

namespace App\Manager\Domain\Model\Entity;

class PreferenceList
{
    public static function emptyList(): self
    {
        return new self();
    }
}
