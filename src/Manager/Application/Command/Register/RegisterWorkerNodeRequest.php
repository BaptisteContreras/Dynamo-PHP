<?php

namespace App\Manager\Application\Command\Register;

use Symfony\Component\Validator\Constraints\Ip;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;

final class RegisterWorkerNodeRequest
{
    /**         Properties         **/
    private const MIN_PORT = 1024;
    private const MAX_PORT = 65535;

    #[NotBlank]
    #[Ip]
    private ?string $networkAddress;

    #[NotBlank]
    #[Range(
        min: self::MIN_PORT,
        max: self::MAX_PORT,
    )]
    private ?int $networkPort;

    #[Range(
        min: -9999,
        max: 9999,
    )]
    private ?int $weight;

    /**         Methods         **/
    /**         Accessors         **/
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
