<?php

namespace App\Manager\Application\Query\WorkerInformations\Search\Presenter;

use App\Manager\Application\Query\WorkerInformations\Search\Response\SearchAllWorkerInformationsResponse;
use App\Manager\Application\Query\WorkerInformations\Search\ViewModel\JsonSearchAllWorkerInformationsViewModel;
use App\Shared\Application\ViewModelInterface;
use App\Shared\Infrastructure\Http\HttpCode;

class JsonSearchAllWorkerInformationsPresenter extends AbstractSearchAllWorkerInformationsPresenter
{
    /**         Properties         **/
    private JsonSearchAllWorkerInformationsViewModel $jsonSearchAllWorkerInformationsViewModel;

    /**         Methods         **/
    public function present(SearchAllWorkerInformationsResponse $searchAllWorkerInformationsResponse): void
    {
        $this->jsonSearchAllWorkerInformationsViewModel = new JsonSearchAllWorkerInformationsViewModel($searchAllWorkerInformationsResponse->getWorkerInformationsReponses());
    }

    public function toViewModel(): ViewModelInterface
    {
        return $this->jsonSearchAllWorkerInformationsViewModel;
    }

    public function getReturnCode(): HttpCode
    {
        return $this->jsonSearchAllWorkerInformationsViewModel->getCode();
    }
}
