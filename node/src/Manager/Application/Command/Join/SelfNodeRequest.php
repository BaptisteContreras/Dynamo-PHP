<?php

namespace App\Manager\Application\Command\Join;

use App\Shared\Domain\Const\RingInformations;
use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;

#[OA\Schema]
final readonly class SelfNodeRequest extends NodeRequest
{
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
    private int $weight;

    #[OA\Property(
        title: 'Is this node a seed',
        type: 'bool',
        example: true,
    )]
    private bool $seed;

    public function __construct(string $host, int $networkPort, int $weight, bool $seed = false)
    {
        parent::__construct($host, $networkPort);

        $this->weight = $weight;
        $this->seed = $seed;
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
