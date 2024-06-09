<?php

namespace App\Background\Domain\Out\History;

use App\Background\Domain\Model\Aggregate\History\History;

interface FinderInterface
{
    public function getLocalHistoryTimeline(): History;
}
