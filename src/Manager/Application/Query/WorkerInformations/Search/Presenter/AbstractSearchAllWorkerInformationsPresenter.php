<?php

namespace App\Manager\Application\Query\WorkerInformations\Search\Presenter;

use App\Manager\Application\Query\WorkerInformations\Search\Response\SearchAllWorkerInformationsResponse;
use App\Shared\Application\ResponsePresenterInterface;

abstract class AbstractSearchAllWorkerInformationsPresenter implements ResponsePresenterInterface
{
    abstract public function present(SearchAllWorkerInformationsResponse $searchAllWorkerInformationsResponse): void;
}
