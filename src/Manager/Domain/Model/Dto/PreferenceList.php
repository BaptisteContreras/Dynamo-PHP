<?php

namespace App\Manager\Domain\Model\Dto;

use App\Manager\Domain\Model\DtoV1Interface;

class PreferenceList implements DtoV1Interface
{
    public static function emptyList(): self
    {
        return new self();
    }
}
