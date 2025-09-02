<?php

namespace App\Admin\Infrastructure\Symfony\Components\Topology;

use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsLiveComponent(template: 'admin/components/topology/TopologyGraph.html.twig')]
class TopologyGraph
{
    use DefaultActionTrait;

    #[LiveProp]
    public array $nodes = [];

    public function mount(): void
    {
        $this->generateNodes();
    }

    public function refresh(): void
    {
        $this->generateNodes();
    }

    private function generateNodes(): void
    {
        $statuses = ['status-up', 'status-pending', 'status-error'];
        $statusLabels = ['ONLINE', 'PENDING', 'ERROR'];
        $nodeNames = ['WEB-01', 'WEB-02', 'DB-01', 'API-01', 'CACHE', 'LB-01', 'FW-01', 'MON-01'];
        
        $this->nodes = [];
        $nodeCount = rand(4, 8); // Random number of nodes for demo
        
        for ($i = 0; $i < $nodeCount; $i++) {
            $this->nodes[] = [
                'name' => $nodeNames[$i] ?? "NODE-" . ($i + 1),
                'status' => $statuses[array_rand($statuses)],
                'statusLabel' => $statusLabels[array_rand($statusLabels)]
            ];
        }
    }

}