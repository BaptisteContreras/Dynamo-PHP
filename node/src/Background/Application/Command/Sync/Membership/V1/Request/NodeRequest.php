<?php

namespace App\Background\Application\Command\Sync\Membership\V1\Request;

use App\Background\Domain\Model\Aggregate\Ring\Collection\VirtualNodeCollection;
use App\Background\Domain\Model\Aggregate\Ring\Node;
use App\Shared\Domain\Const\NodeState;
use App\Shared\Domain\Const\RingInformations;
use OpenApi\Attributes as OA;
use Symfony\Component\Uid\UuidV7;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Constraints\Uuid;
use Symfony\Component\Validator\Constraints\Valid;

final readonly class NodeRequest
{
    private const MIN_PORT = 1024;
    private const MAX_PORT = 65535;

    #[OA\Property(
        title: 'ID of the node',
    )]
    #[Uuid]
    private string $id;

    #[OA\Property(
        title: 'Host of the node',
        description: 'Network host of the node',
        type: 'string',
        example: 'localhost',
    )]
    #[NotBlank]
    #[Length(min: 3, max: 255)]
    private string $host;

    /**
     * @var positive-int
     */
    #[OA\Property(
        title: 'Port of the node',
        description: 'The port on which the DynamoPHP-node service listen to',
        type: 'integer',
        maximum: self::MAX_PORT,
        minimum: self::MIN_PORT,
        example: 9003,
    )]
    #[NotBlank]
    #[Range(
        min: self::MIN_PORT,
        max: self::MAX_PORT,
    )]
    private int $networkPort;

    #[OA\Property(
        title: 'State of the node',
    )]
    private NodeState $state;

    #[OA\Property(
        title: 'When the node joined the ring',
    )]
    private \DateTimeImmutable $joinedAt;

    #[OA\Property(
        title: 'Weight of the node in the ring',
    )]
    #[Range(
        min: 1,
        max: RingInformations::MAX_SLOTS,
    )]
    private int $weight;

    #[OA\Property(
        title: 'Is this node a seed',
        type: 'bool',
        example: true,
    )]
    private bool $seed;

    #[OA\Property(
        title: 'Last time this node informations has been updated',
    )]
    private \DateTimeImmutable $updatedAt;

    #[OA\Property(
        title: 'Label of the node',
        description: 'Unique label for the node in the ring',
        type: 'string',
        example: 'A',
    )]
    #[NotBlank]
    #[Length(min: 1, max: 10)]
    private string $label;

    /** @var array<VirtualNodeRequest> */
    #[OA\Property(
        title: 'Virtual nodes',
        description: 'All nodes to sync',
    )]
    #[Valid]
    #[NotBlank(message: 'Virtual node list cannot be empty')]
    private array $virtualNodes;

    /**
     * @param array<VirtualNodeRequest> $virtualNodes
     * @param positive-int              $networkPort
     */
    public function __construct(string $id, string $host, int $networkPort, NodeState $state, \DateTimeImmutable $joinedAt, int $weight, bool $seed, \DateTimeImmutable $updatedAt, string $label, array $virtualNodes)
    {
        $this->id = $id;
        $this->host = $host;
        $this->networkPort = $networkPort;
        $this->state = $state;
        $this->joinedAt = $joinedAt;
        $this->weight = $weight;
        $this->seed = $seed;
        $this->updatedAt = $updatedAt;
        $this->label = $label;
        $this->virtualNodes = $virtualNodes;
    }

    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * @return positive-int
     */
    public function getNetworkPort(): int
    {
        return $this->networkPort;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getState(): NodeState
    {
        return $this->state;
    }

    public function getJoinedAt(): \DateTimeImmutable
    {
        return $this->joinedAt;
    }

    public function getWeight(): int
    {
        return $this->weight;
    }

    public function isSeed(): bool
    {
        return $this->seed;
    }

    public function getUpdatedAt(): \DateTimeImmutable
    {
        return $this->updatedAt;
    }

    /**
     * @return array<VirtualNodeRequest>
     */
    public function getVirtualNodes(): array
    {
        return $this->virtualNodes;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function asDto(): Node
    {
        $virtualNodeCollection = VirtualNodeCollection::createEmpty();

        $nodeDto = new Node(
            UuidV7::fromString($this->id),
            $this->host,
            $this->networkPort,
            $this->state,
            $this->joinedAt,
            $this->weight,
            $this->seed,
            $this->updatedAt,
            $this->label,
            $virtualNodeCollection
        );

        $virtualNodeCollection->merge(
            new VirtualNodeCollection(
                array_map(fn (VirtualNodeRequest $virtualNodeRequest) => $virtualNodeRequest->asDto($nodeDto), $this->virtualNodes)
            )
        );

        return $nodeDto;
    }
}
