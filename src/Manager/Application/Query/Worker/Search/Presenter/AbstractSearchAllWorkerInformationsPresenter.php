<?php

namespace App\Manager\Application\Query\Worker\Search\Presenter;

use App\Manager\Application\Query\Worker\Search\Response\SearchAllWorkerInformationsResponse;
use App\Shared\Application\ResponsePresenter;

abstract class AbstractSearchAllWorkerInformationsPresenter extends ResponsePresenter
{
    abstract public function present(SearchAllWorkerInformationsResponse $searchAllWorkerInformationsResponse): void;
}
