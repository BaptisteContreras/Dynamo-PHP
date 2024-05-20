<?php

namespace App\Manager\Application\Command\Join\Request;

use App\Shared\Domain\Const\RingInformations;
use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;

#[OA\Schema]
final readonly class SelfNodeRequest extends NodeRequest
{
    #[OA\Property(
        title: 'Weight of the node in the ring',
        description: 'How many slots should be assigned to the node',
        type: 'integer',
        maximum: RingInformations::MAX_SLOTS,
        minimum: 1,
        example: 3,
    )]
    #[NotBlank]
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
        title: 'Label of the node',
        description: 'Unique label for the node in the ring',
        type: 'string',
        example: 'A',
    )]
    #[NotBlank]
    #[Length(min: 1, max: 10)]
    protected string $label;

    public function __construct(string $host, int $networkPort, int $weight, string $label, bool $seed = false)
    {
        parent::__construct($host, $networkPort);

        $this->weight = $weight;
        $this->seed = $seed;
        $this->label = $label;
    }

    /**
     * @return positive-int
     */
    public function getWeight(): int
    {
        return $this->weight;
    }

    public function isSeed(): bool
    {
        return $this->seed;
    }

    public function getLabel(): string
    {
        return $this->label;
    }
}
