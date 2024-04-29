<?php

namespace App\Manager\Application\Query\Worker\Search\Presenter;

use App\Manager\Application\Query\Worker\Search\Response\SearchAllWorkerInformationsResponse;
use App\Manager\Application\Query\Worker\Search\ViewModel\JsonSearchAllWorkerInformationsViewModel;

class JsonPresenter extends SearchAllWorkerInformationsPresenter
{
    public function present(SearchAllWorkerInformationsResponse $searchAllWorkerInformationsResponse): void
    {
        $this->viewModel = new JsonSearchAllWorkerInformationsViewModel($searchAllWorkerInformationsResponse->getWorkerInformationsResponses());
    }
}
