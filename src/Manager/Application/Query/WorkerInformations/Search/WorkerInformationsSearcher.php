<?php

namespace App\Manager\Application\Query\WorkerInformations\Search;

use App\Manager\Domain\Contract\Out\Repository\WorkerInformationsRepositoryInterface;
use App\Manager\Domain\Model\Dto\WorkerNode;

class WorkerInformationsSearcher
{
    /**         Constructor         **/
    public function __construct(private readonly WorkerInformationsRepositoryInterface $workerInformationsRepository)
    {
    }

    /**         Methods         **/

    /**
     * @return array<WorkerNode>
     */
    public function searchAll(): array
    {
        return $this->workerInformationsRepository->findAll();
    }
}
