<?php

namespace App\Background\Domain\Model\Aggregate\History\Collection;

use App\Background\Domain\Model\Aggregate\History\HistoryEvent;
use App\Shared\Domain\Model\Collection\MutableCollection;
use App\Shared\Domain\Model\Collection\MutableCollectionInterface;

/**
 * @implements MutableCollectionInterface<HistoryEvent>
 */
class HistoryEventCollection extends RoHistoryEventCollection implements MutableCollectionInterface
{
    /**
     * @use MutableCollection<HistoryEvent>
     */
    use MutableCollection;
}
