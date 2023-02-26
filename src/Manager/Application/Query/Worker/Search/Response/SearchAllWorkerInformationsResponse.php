<?php

namespace App\Manager\Application\Query\Worker\Search\Response;

use App\Manager\Domain\Model\Entity\WorkerNode;
use App\Shared\Application\ApplicationResponseInterface;

final class SearchAllWorkerInformationsResponse implements ApplicationResponseInterface
{
    /**
     * @param array<SearchWorkerInformationsResponse> $workerInformationsReponses
     */
    public function __construct(private readonly array $workerInformationsReponses)
    {
    }

    /**
     * @return array<SearchWorkerInformationsResponse>
     */
    public function getWorkerInformationsReponses(): array
    {
        return $this->workerInformationsReponses;
    }

    /**
     * @param array<WorkerNode> $workerInformations
     */
    public static function buildFromWorkerInformationsArray(array $workerInformations): self
    {
        return new self(
            array_map(function (WorkerNode $workerInformations) {
                return SearchWorkerInformationsResponse::buildFromWorkerInformations($workerInformations);
            }, $workerInformations)
        );
    }
}
