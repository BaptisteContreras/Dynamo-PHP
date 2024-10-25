<?php

namespace App\Foreground\Domain\Out\Node;

use App\Foreground\Domain\Model\Aggregate\Node\Node;

interface FinderInterface
{
    public function getLocalEntry(): Node;
}
