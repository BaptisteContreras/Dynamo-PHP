<?php

namespace App\Background\Domain\Out\History;

use App\Background\Domain\Model\Aggregate\History\HistoryTimeline;

interface UpdaterInterface
{
    public function saveHistoryTimeline(HistoryTimeline $historyTimeline): void;
}
