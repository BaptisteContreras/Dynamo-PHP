<?php

namespace App\Manager\Application\Query\WorkerInformations\Search;

use App\Manager\Domain\Contract\Repository\WorkerInformationsRepositoryInterface;
use App\Manager\Domain\Model\Dto\WorkerInformations;

class WorkerInformationsSearcher
{
    /**         Constructor         **/
    public function __construct(private readonly WorkerInformationsRepositoryInterface $workerInformationsRepository)
    {
    }

    /**         Methods         **/

    /**
     * @return array<WorkerInformations>
     */
    public function searchAll(): array
    {
        return $this->workerInformationsRepository->findAll();
    }
}
