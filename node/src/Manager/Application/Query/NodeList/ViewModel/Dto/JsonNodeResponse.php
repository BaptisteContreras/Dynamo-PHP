<?php

namespace App\Manager\Application\Query\NodeList\ViewModel\Dto;

use App\Shared\Domain\Const\NodeState;
use OpenApi\Attributes as OA;

final readonly class JsonNodeResponse
{
    /**
     * @param array<JsonVirtualNodeResponse> $virtualNodes
     */
    public function __construct(
        private string $id,
        private string $host,
        private int $networkPort,
        private NodeState $membershipState,
        private \DateTimeImmutable $joinedAt,
        private int $weight,
        private bool $seed,
        private string $label,
        private array $virtualNodes
    ) {
    }

    #[OA\Property(
        example: 'abcf3554-c788-7a58-a441-89421dabad90'
    )]
    public function getId(): string
    {
        return $this->id;
    }

    #[OA\Property(
        example: 'localhost'
    )]
    public function getHost(): string
    {
        return $this->host;
    }

    #[OA\Property(
        example: 8080
    )]
    public function getNetworkPort(): int
    {
        return $this->networkPort;
    }

    public function getMembershipState(): NodeState
    {
        return $this->membershipState;
    }

    public function getJoinedAt(): \DateTimeImmutable
    {
        return $this->joinedAt;
    }

    #[OA\Property(
        example: 3
    )]
    public function getWeight(): int
    {
        return $this->weight;
    }

    public function isSeed(): bool
    {
        return $this->seed;
    }

    #[OA\Property(
        example: 'B'
    )]
    public function getLabel(): string
    {
        return $this->label;
    }

    public function getVirtualNodes(): array
    {
        return $this->virtualNodes;
    }
}
