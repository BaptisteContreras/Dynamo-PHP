<?php

namespace App\Manager\Application\Command\Worker\Register;

use App\Manager\Domain\Constante\RingInformations;
use Symfony\Component\Validator\Constraints\Ip;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;

final class RegisterWorkerNodeRequest
{
    private const MIN_PORT = 1024;
    private const MAX_PORT = 65535;

    #[NotBlank]
    #[Ip]
    private ?string $networkAddress = null;

    #[NotBlank]
    #[Range(
        min: self::MIN_PORT,
        max: self::MAX_PORT,
    )]
    private ?int $networkPort = null;

    #[NotBlank]
    #[Range(
        min: 1,
        max: RingInformations::MAX_LABEL_SLOTS, // TODO must be dynamic
    )]
    private ?int $weight = null;

    public function getNetworkAddress(): string
    {
        return $this->networkAddress;
    }

    public function getNetworkPort(): int
    {
        return $this->networkPort;
    }

    public function getWeight(): ?int
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
