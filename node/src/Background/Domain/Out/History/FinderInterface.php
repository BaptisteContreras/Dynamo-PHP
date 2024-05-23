<?php

namespace App\Background\Domain\Out\History;

use App\Background\Domain\Model\Aggregate\History\HistoryTimeline;

interface FinderInterface
{
    public function getLocalHistoryTimeline(): HistoryTimeline;
}
