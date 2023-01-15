<?php

namespace App\Manager\Infrastructure\Symfony\Controller;

use App\Manager\Application\Query\Worker\Search\Presenter\SearchAllWorkerInformationsPresenter;
use App\Manager\Application\Query\Worker\Search\Request\SearchAllWorkerInformationsRequest;
use App\Manager\Application\Query\Worker\Search\SearchAllWorkerInformationsQueryHandler;
use App\Shared\Infrastructure\Symfony\Controller\AbstractApiController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/workers', name: 'workers_')]
class WorkersController extends AbstractApiController
{
    #[Route(path: '/', name: 'list')]
    public function listAll(SearchAllWorkerInformationsQueryHandler $searchAllWorkerInformationsQueryHandler): Response
    {
        // We only supports JSON content type for the moment.
        $presenter = SearchAllWorkerInformationsPresenter::json();

        $searchAllWorkerInformationsQueryHandler(SearchAllWorkerInformationsRequest::build(), $presenter);

        return $this->buildJsonResponse($presenter);
    }
}
