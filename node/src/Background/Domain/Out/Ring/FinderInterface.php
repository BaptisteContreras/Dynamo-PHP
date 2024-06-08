<?php

namespace App\Background\Domain\Out\Ring;

use App\Background\Domain\Model\Aggregate\Ring\Ring;

interface FinderInterface
{
    public function getLocalRing(): Ring;
}
