<?php

namespace App\Manager\Application\Query\WorkerInformations\Search\Response;

use App\Manager\Domain\Model\Dto\WorkerInformations;
use App\Shared\Application\ApplicationResponseInterface;

final class SearchAllWorkerInformationsResponse implements ApplicationResponseInterface
{
    /**         Constructor         **/

    /** @var array<SearchWorkerInformationsResponse> */
    public function __construct(private readonly array $workerInformationsReponses)
    {
    }

    /**         Accessors         **/
    public function getWorkerInformationsReponses(): array
    {
        return $this->workerInformationsReponses;
    }

    /**         Methods         **/

    /**
     * @param array<WorkerInformations> $workerInformations
     */
    public static function buildFromWorkerInformationsArray(array $workerInformations): self
    {
        return new self(
            array_map(function (WorkerInformations $workerInformations) {
                return SearchWorkerInformationsResponse::buildFromWorkerInformations($workerInformations);
            }, $workerInformations)
        );
    }
}
