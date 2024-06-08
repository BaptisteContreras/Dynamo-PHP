<?php

namespace App\Background\Domain\Out\Ring;

use App\Background\Domain\Model\Aggregate\Ring\Ring;

interface UpdaterInterface
{
    public function saveRing(Ring $ring): void;
}
