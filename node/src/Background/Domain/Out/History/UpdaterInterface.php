<?php

namespace App\Background\Domain\Out\History;

use App\Background\Domain\Model\Aggregate\History\History;

interface UpdaterInterface
{
    public function saveHistoryTimeline(History $historyTimeline): void;
}
