<?php

namespace App\Manager\Application\Command\Join\Request;

use App\Manager\Domain\Service\VirtualNode\Attribution\Strategy\RandomStrategy;
use App\Shared\Domain\Const\RingInformations;
use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\NotBlank;

final readonly class ConfigRequest
{
    #[OA\Property(
        title: 'Virtual node attribution strategy',
        description: 'Strategy to initialize the virtual node int the ring for this node',
        type: 'string',
        maximum: RingInformations::MAX_SLOTS,
        example: RandomStrategy::NAME,
    )]
    #[NotBlank]
    #[Choice(choices: [RandomStrategy::NAME])]
    private string $virtualNodeAttributionStrategy;

    public function __construct(string $virtualNodeAttributionStrategy)
    {
        $this->virtualNodeAttributionStrategy = $virtualNodeAttributionStrategy;
    }

    public function getVirtualNodeAttributionStrategy(): string
    {
        return $this->virtualNodeAttributionStrategy;
    }
}
