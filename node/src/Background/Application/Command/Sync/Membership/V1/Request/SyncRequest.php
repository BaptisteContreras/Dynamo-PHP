<?php

namespace App\Background\Application\Command\Sync\Membership\V1\Request;

use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Uuid;
use Symfony\Component\Validator\Constraints\Valid;

final readonly class SyncRequest
{
    #[OA\Property(
        title: 'ID of the node sending the request',
    )]
    #[Uuid]
    private string $sourceNode;

    /** @var array<HistoryEventRequest> */
    #[OA\Property(
        title: 'History',
        description: 'All events for the history',
    )]
    #[Valid]
    #[NotBlank(message: 'History cannot be empty')]
    private array $historyEvents;

    /**
     * @param array<HistoryEventRequest> $historyEvents
     */
    public function __construct(string $sourceNode, array $historyEvents)
    {
        $this->sourceNode = $sourceNode;
        $this->historyEvents = $historyEvents;
    }

    /**
     * @return array<HistoryEventRequest>
     */
    public function getHistoryEvents(): array
    {
        return $this->historyEvents;
    }

    public function getSourceNode(): string
    {
        return $this->sourceNode;
    }
}
