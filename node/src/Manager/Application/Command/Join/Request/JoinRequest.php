<?php

namespace App\Manager\Application\Command\Join\Request;

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

    #[OA\Property(
        title: 'Ring configuration',
    )]
    #[Valid]
    private ConfigRequest $config;

    /**
     * @param array<SeedRequest> $initialSeeds
     */
    public function __construct(SelfNodeRequest $selfNode, ConfigRequest $config, array $initialSeeds = [])
    {
        $this->selfNode = $selfNode;
        $this->initialSeeds = $initialSeeds;
        $this->config = $config;
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

    public function getConfig(): ConfigRequest
    {
        return $this->config;
    }
}
