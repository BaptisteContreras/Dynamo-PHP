<?php

namespace App\Manager\Application\Command\Join;

use App\Shared\Domain\Const\RingInformations;
use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;

final class JoinRequest
{
    private const MIN_PORT = 1024;
    private const MAX_PORT = 65535;

    #[OA\Property(
        title: 'Host of the node',
        description: 'Network host of the node',
        type: 'string',
        example: 'localhost',
    )]
    #[NotBlank]
    #[Length(min: 3, max: 255)]
    private ?string $host = null;

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
    private ?int $networkPort = null;

    #[OA\Property(
        title: 'Weight of the node in the ring',
        description: 'How many slots should be assigned to the node',
        type: 'integer',
        maximum: RingInformations::MAX_LABEL_SLOTS,
        minimum: 1,
        example: 3,
    )]
    #[NotBlank]
    #[Range(
        min: 1,
        max: RingInformations::MAX_LABEL_SLOTS,
    )]
    private ?int $weight = null;

    #[OA\Property(
        title: 'Is this node a seed',
        type: 'bool',
        example: true,
    )]
    private bool $seed = false;

    public function __construct(?string $host, ?int $networkPort, ?int $weight)
    {
        $this->host = $host;
        $this->networkPort = $networkPort;
        $this->weight = $weight;
    }

    public function getHost(): string
    {
        return $this->host;
    }

    public function getNetworkPort(): int
    {
        return $this->networkPort;
    }

    public function getWeight(): int
    {
        return $this->weight;
    }

    public function isSeed(): bool
    {
        return $this->seed;
    }
}
