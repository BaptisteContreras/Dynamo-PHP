<?php

namespace App\Background\Application\Command\Sync\Membership\V1\Request;

use App\Shared\Domain\Const\HistoryEventType;
use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints\Uuid;

final readonly class HistoryEventRequest
{
    #[OA\Property(
        title: 'ID of the event',
    )]
    #[Uuid]
    private string $id;

    #[OA\Property(
        title: 'ID of the node',
        description: 'ID of the node concerned by the event',
    )]
    #[Uuid]
    private string $node;

    #[OA\Property(
        title: 'Type of event',
    )]
    private HistoryEventType $type;

    #[OA\Property(
        title: 'Data of the event',
    )]
    private ?string $data;

    #[OA\Property(
        title: 'Timestamp of the event',
    )]
    private \DateTimeImmutable $eventTime;

    public function __construct(string $id, string $node, HistoryEventType $type, ?string $data, \DateTimeImmutable $eventTime)
    {
        $this->id = $id;
        $this->node = $node;
        $this->type = $type;
        $this->data = $data;
        $this->eventTime = $eventTime;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getNode(): string
    {
        return $this->node;
    }

    public function getType(): HistoryEventType
    {
        return $this->type;
    }

    public function getData(): ?string
    {
        return $this->data;
    }

    public function getEventTime(): \DateTimeImmutable
    {
        return $this->eventTime;
    }
}
