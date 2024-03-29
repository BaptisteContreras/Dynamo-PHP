<?php

namespace App\Manager\Application\Query\Worker\Search\Response;

use App\Manager\Domain\Model\Entity\WorkerNode;
use App\Shared\Application\ApplicationResponseInterface;

final class SearchAllWorkerInformationsResponse implements ApplicationResponseInterface
{
    /**
     * @param array<SearchWorkerInformationsResponse> $workerInformationsResponses
     */
    public function __construct(private readonly array $workerInformationsResponses)
    {
    }

    /**
     * @return array<SearchWorkerInformationsResponse>
     */
    public function getWorkerInformationsResponses(): array
    {
        return $this->workerInformationsResponses;
    }

    /**
     * @param array<WorkerNode> $workerInformations
     */
    public static function buildFromWorkerInformationsArray(array $workerInformations): self
    {
        return new self(
            array_map(function (WorkerNode $workerNode) {
                return SearchWorkerInformationsResponse::buildFromWorkerNodeDto($workerNode->readOnly());
            }, $workerInformations)
        );
    }
}
