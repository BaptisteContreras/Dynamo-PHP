<?php

namespace App\Manager\Application\Command\Join;

use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints\Valid;

final readonly class JoinRequest
{
    #[OA\Property(
        title: 'Self node informations',
        description: 'Informations about this node',
    )]
    #[Valid]
    private SelfNodeRequest $selfNode;

    /** @var array<SeedRequest> */
    #[OA\Property(
        title: 'Initial seeds',
        description: 'Seeds that will be contacted first',
    )]
    #[Valid]
    private array $initialSeeds;

    /**
     * @param array<SeedRequest> $initialSeeds
     */
    public function __construct(SelfNodeRequest $selfNode, array $initialSeeds = [])
    {
        $this->selfNode = $selfNode;
        $this->initialSeeds = $initialSeeds;
    }

    public function getSelfNode(): SelfNodeRequest
    {
        return $this->selfNode;
    }

    /**
     * @return array<SeedRequest>
     */
    public function getInitialSeeds(): array
    {
        return $this->initialSeeds;
    }
}
