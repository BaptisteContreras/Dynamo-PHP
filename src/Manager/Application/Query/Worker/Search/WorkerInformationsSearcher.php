<?php

namespace App\Manager\Application\Query\Worker\Search;

use App\Manager\Domain\Contract\Out\Finder\WorkerNodeFinderInterface;
use App\Manager\Domain\Model\Entity\WorkerNode;

class WorkerInformationsSearcher
{
    public function __construct(private readonly WorkerNodeFinderInterface $workerNodeFinder)
    {
    }

    /**
     * @return array<WorkerNode>
     */
    public function searchAll(): array
    {
        return $this->workerNodeFinder->findAll();
    }
}
