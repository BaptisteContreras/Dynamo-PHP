<?php

namespace App\Manager\Application\Command\Worker\Join;

use App\Manager\Domain\Constante\RingInformations;
use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints\Ip;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;

final class JoinRequest
{
    private const MIN_PORT = 1024;
    private const MAX_PORT = 65535;

    #[NotBlank]
    #[Ip]
    #[OA\Property(
        title: 'IPv4 of the node',
        description: 'This field must respect the IPv4 format',
        type: 'string',
        example: '127.0.0.1',
    )]
    private ?string $networkAddress = null;

    #[NotBlank]
    #[Range(
        min: self::MIN_PORT,
        max: self::MAX_PORT,
    )]
    #[OA\Property(
        title: 'Port of the node',
        description: 'The port on which the DynamoPHP-worker service listen to',
        type: 'integer',
        example: 9003,
        minimum: self::MIN_PORT,
        maximum: self::MAX_PORT,
    )]
    private ?int $networkPort = null;

    #[NotBlank]
    #[Range(
        min: 1,
        max: RingInformations::MAX_LABEL_SLOTS, // TODO must be dynamic
    )]
    #[OA\Property(
        title: 'Weight of the node in the ring',
        description: 'How many slots should be assigned to the node',
        type: 'integer',
        example: 3,
        minimum: 1,
        maximum: RingInformations::MAX_LABEL_SLOTS,
    )]
    private ?int $weight = null;

    /**
     * Phpstan spots a type mismatch but this method must be called after the validation of this object.
     * This phpstan error is ignored in the baseline for the moment.
     */
    public function getNetworkAddress(): string
    {
        return $this->networkAddress;
    }

    /**
     * Phpstan spots a type mismatch but this method must be called after the validation of this object.
     * This phpstan error is ignored in the baseline for the moment.
     */
    public function getNetworkPort(): int
    {
        return $this->networkPort;
    }

    /**
     * Phpstan spots a type mismatch but this method must be called after the validation of this object.
     * This phpstan error is ignored in the baseline for the moment.
     */
    public function getWeight(): int
    {
        return $this->weight;
    }

    public function setNetworkAddress(?string $networkAddress): void
    {
        $this->networkAddress = $networkAddress;
    }

    public function setNetworkPort(?int $networkPort): void
    {
        $this->networkPort = $networkPort;
    }

    public function setWeight(?int $weight): void
    {
        $this->weight = $weight;
    }
}
