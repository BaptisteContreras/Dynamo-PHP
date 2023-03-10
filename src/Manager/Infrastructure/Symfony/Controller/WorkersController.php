<?php

namespace App\Manager\Infrastructure\Symfony\Controller;

use App\Manager\Application\Command\Worker\Join\JoinCommandHandler;
use App\Manager\Application\Command\Worker\Join\JoinRequest;
use App\Manager\Application\Command\Worker\Join\Presenter\JoinPresenter;
use App\Manager\Application\Command\Worker\Leave\LeaveCommandHandler;
use App\Manager\Application\Command\Worker\Leave\LeaveRequest;
use App\Manager\Application\Command\Worker\Leave\Presenter\LeavePresenter;
use App\Manager\Application\Query\Worker\Search\Presenter\SearchAllWorkerInformationsPresenter;
use App\Manager\Application\Query\Worker\Search\Request\SearchAllWorkerInformationsRequest;
use App\Manager\Application\Query\Worker\Search\SearchAllWorkerInformationsQueryHandler;
use App\Shared\Infrastructure\Symfony\Controller\AbstractApiController;
use BaptisteContreras\SymfonyRequestParamBundle\Configuration\AutoProvideRequestDto;
use BaptisteContreras\SymfonyRequestParamBundle\Configuration\DtoRequestParam;
use BaptisteContreras\SymfonyRequestParamBundle\Model\SourceType;
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

    #[Route(path: '/join', name: 'join', methods: ['POST'])]
    #[AutoProvideRequestDto]
    public function join(
        #[DtoRequestParam(sourceType: SourceType::JSON, validateDto: false)] JoinRequest $joinWorkerNodeRequest,
        JoinCommandHandler $joinWorkerNodeCommandHandler
    ): Response {
        $presenter = JoinPresenter::json();

        $joinWorkerNodeCommandHandler($joinWorkerNodeRequest, $presenter);

        return $this->buildJsonResponse($presenter);
    }

    #[Route(path: '/{workerId}/leave', name: 'leave', methods: ['DELETE'])]
    #[AutoProvideRequestDto]
    public function leave(
        int $workerId,
        LeaveCommandHandler $leftCommandHandler
    ): Response {
        $presenter = LeavePresenter::json();

        $leftCommandHandler(LeaveRequest::build($workerId), $presenter);

        return $this->buildJsonResponse($presenter);
    }
}
