<?php

namespace App\Manager\Application\Query\Worker\Search;

use App\Manager\Application\Query\Worker\Search\Presenter\SearchAllWorkerInformationsPresenter;
use App\Manager\Application\Query\Worker\Search\Request\SearchAllWorkerInformationsRequest;
use App\Manager\Application\Query\Worker\Search\Response\SearchAllWorkerInformationsResponse;

class SearchAllWorkerInformationsQueryHandler
{
    /**         Constructor         **/
    public function __construct(private readonly WorkerInformationsSearcher $workerInformationsSearcher)
    {
    }

    /**         Methods         **/
    public function __invoke(
        SearchAllWorkerInformationsRequest $searchAllWorkerInformationsRequest,
        SearchAllWorkerInformationsPresenter $abstractSearchAllWorkerInformationsPresenter
    ): void {
        $abstractSearchAllWorkerInformationsPresenter->present(
            SearchAllWorkerInformationsResponse::buildFromWorkerInformationsArray($this->workerInformationsSearcher->searchAll())
        );
    }
}
