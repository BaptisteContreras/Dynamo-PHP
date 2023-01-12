<?php

namespace App\Manager\Application\Query\WorkerInformations\Search\Response;

use App\Manager\Domain\Model\Dto\WorkerNode;
use App\Shared\Application\ApplicationResponseInterface;

final class SearchAllWorkerInformationsResponse implements ApplicationResponseInterface
{
    /** @var array<SearchWorkerInformationsResponse> */
    public function __construct(private readonly array $workerInformationsReponses)
    {
    }

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
