<?php

namespace App\Manager\Application\Command\Join;

use App\Shared\Domain\Const\RingInformations;
use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints\Ip;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;

final class JoinRequest
{
    private const MIN_PORT = 1024;
    private const MAX_PORT = 65535;

    #[OA\Property(
        title: 'IPv4 of the node',
        description: 'This field must respect the IPv4 format',
        type: 'string',
        example: '127.0.0.1',
    )]
    #[NotBlank]
    #[Ip]
    private ?string $networkAddress = null;

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

    public function __construct(?string $networkAddress, ?int $networkPort, ?int $weight)
    {
        $this->networkAddress = $networkAddress;
        $this->networkPort = $networkPort;
        $this->weight = $weight;
    }

    public function getNetworkAddress(): string
    {
        return $this->networkAddress;
    }

    public function getNetworkPort(): int
    {
        return $this->networkPort;
    }

    public function getWeight(): int
    {
        return $this->weight;
    }
}
