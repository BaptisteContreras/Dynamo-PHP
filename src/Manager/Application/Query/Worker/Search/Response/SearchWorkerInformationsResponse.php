<?php

namespace App\Manager\Application\Query\Worker\Search\Response;

use App\Manager\Domain\Constante\Enum\WorkerState;
use App\Manager\Domain\Model\Dto\WorkerNodeDto;
use App\Shared\Application\ApplicationResponseInterface;
use OpenApi\Attributes as OA;

final class SearchWorkerInformationsResponse implements ApplicationResponseInterface
{
    public function __construct(
        private readonly int $id,
        private readonly string $networkAddress,
        private readonly int $networkPort,
        private readonly \DateTimeImmutable $joinedAt,
        private readonly string $label,
        private readonly int $weight,
        private readonly string $workerState,
    ) {
    }

    public static function buildFromWorkerNodeDto(WorkerNodeDto $workerNodeDto): self
    {
        return new self(
            $workerNodeDto->getId(),
            $workerNodeDto->getNetworkAddress(),
            $workerNodeDto->getNetworkPort(),
            $workerNodeDto->getJoinedAt(),
            $workerNodeDto->getLabelName(),
            $workerNodeDto->getWeight(),
            $workerNodeDto->getWorkerState()->value
        );
    }

    #[OA\Property(
        description: 'The unique ID of the worker in the ring',
        example: 1
    )]
    public function getId(): int
    {
        return $this->id;
    }

    #[OA\Property(
        title: 'IPv4 of the node',
        type: 'string',
        example: '127.0.0.1',
    )]
    public function getNetworkAddress(): string
    {
        return $this->networkAddress;
    }

    #[OA\Property(
        title: 'Port of the node',
        type: 'integer',
        example: 9003
    )]
    public function getNetworkPort(): int
    {
        return $this->networkPort;
    }

    #[OA\Property(
        title: 'The Date and Time at which the worker node joined the ring',
        type: 'datetime',
        example: '2023-01-01T15:30:00+00:00'
    )]
    public function getJoinedAt(): \DateTimeImmutable
    {
        return $this->joinedAt;
    }

    #[OA\Property(
        description: 'The unique label of the worker in the ring',
        example: 'A'
    )]
    public function getLabel(): string
    {
        return $this->label;
    }

    #[OA\Property(
        title: 'Weight of the node in the ring',
        description: 'How many slots should be assigned to the node',
        type: 'integer',
        example: 3
    )]
    public function getWeight(): int
    {
        return $this->weight;
    }

    #[OA\Property(
        title: 'The state of the worker node',
        type: 'string',
        example: WorkerState::UP,
    )]
    public function getWorkerState(): string
    {
        return $this->workerState;
    }
}
