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

    /** @var array<NodeRequest> */
    #[OA\Property(
        title: 'Nodes',
        description: 'All nodes to sync',
    )]
    #[Valid]
    #[NotBlank(message: 'Node list cannot be empty')]
    private array $nodes;

    /**
     * @param array<HistoryEventRequest> $historyEvents
     * @param array<NodeRequest>         $nodes
     */
    public function __construct(string $sourceNode, array $historyEvents, array $nodes)
    {
        $this->sourceNode = $sourceNode;
        $this->historyEvents = $historyEvents;
        $this->nodes = $nodes;
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

    /**
     * @return array<NodeRequest>
     */
    public function getNodes(): array
    {
        return $this->nodes;
    }
}
