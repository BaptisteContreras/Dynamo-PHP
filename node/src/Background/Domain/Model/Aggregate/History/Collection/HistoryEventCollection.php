<?php

namespace App\Background\Domain\Model\Aggregate\History\Collection;

use App\Background\Domain\Model\Aggregate\History\Event;
use App\Shared\Domain\Model\Collection\MutableCollection;
use App\Shared\Domain\Model\Collection\MutableCollectionInterface;

/**
 * @implements MutableCollectionInterface<Event>
 */
class HistoryEventCollection extends RoHistoryEventCollection implements MutableCollectionInterface
{
    /**
     * @use MutableCollection<Event>
     */
    use MutableCollection;
}
