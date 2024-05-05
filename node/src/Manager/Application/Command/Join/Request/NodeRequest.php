<?php

namespace App\Manager\Application\Command\Join\Request;

use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;

abstract readonly class NodeRequest
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
    protected string $host;

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
    protected int $networkPort;

    public function __construct(string $host, int $networkPort)
    {
        $this->host = $host;
        $this->networkPort = $networkPort;
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
        /* @var positive-int */
        return $this->networkPort;
    }
}
