<?php

namespace App\Manager\Application\Query\WorkerInformations\Search;

use App\Manager\Application\Query\WorkerInformations\Search\Presenter\AbstractSearchAllWorkerInformationsPresenter;
use App\Manager\Application\Query\WorkerInformations\Search\Request\SearchAllWorkerInformationsRequest;
use App\Manager\Application\Query\WorkerInformations\Search\Response\SearchAllWorkerInformationsResponse;

class SearchAllWorkerInformationsQueryHandler
{
    /**         Constructor         **/
    public function __construct(private readonly WorkerInformationsSearcher $workerInformationsSearcher)
    {
    }

    /**         Methods         **/
    public function __invoke(
        SearchAllWorkerInformationsRequest $searchAllWorkerInformationsRequest,
        AbstractSearchAllWorkerInformationsPresenter $abstractSearchAllWorkerInformationsPresenter
    ): void {
        $abstractSearchAllWorkerInformationsPresenter->present(
            SearchAllWorkerInformationsResponse::buildFromWorkerInformationsArray($this->workerInformationsSearcher->searchAll())
        );
    }
}
