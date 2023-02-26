<?php

namespace App\Manager\Application\Query\Worker\Search;

use App\Manager\Domain\Contract\Out\Finder\WorkerNodeFinder;
use App\Manager\Domain\Model\Entity\WorkerNode;

class WorkerInformationsSearcher
{
    /**         Constructor         **/
    public function __construct(private readonly WorkerNodeFinder $workerNodeFinder)
    {
    }

    /**         Methods         **/

    /**
     * @return array<WorkerNode>
     */
    public function searchAll(): array
    {
        return $this->workerNodeFinder->findAll();
    }
}