<?php

namespace App\Manager\Domain\Out\Ring;

use App\Manager\Domain\Model\Aggregate\Ring\Ring;

interface FinderInterface
{
    public function getLocalRing(): Ring;
}
