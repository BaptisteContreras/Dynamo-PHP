<?php

namespace App\Manager\Infrastructure\Symfony\Controller;

use App\Manager\Application\Command\Worker\Join\JoinCommandHandler;
use App\Manager\Application\Command\Worker\Join\JoinRequest;
use App\Manager\Application\Command\Worker\Join\Presenter\JoinPresenter;
use App\Manager\Application\Command\Worker\Join\ViewModel\JsonSuccessViewModel;
use App\Manager\Application\Command\Worker\Leave\LeaveCommandHandler;
use App\Manager\Application\Command\Worker\Leave\LeaveRequest;
use App\Manager\Application\Command\Worker\Leave\Presenter\LeavePresenter;
use App\Manager\Application\Query\Worker\Search\Presenter\SearchAllWorkerInformationsPresenter;
use App\Manager\Application\Query\Worker\Search\Request\SearchAllWorkerInformationsRequest;
use App\Manager\Application\Query\Worker\Search\SearchAllWorkerInformationsQueryHandler;
use App\Manager\Application\Query\Worker\Search\ViewModel\JsonSearchAllWorkerInformationsViewModel;
use App\Shared\Infrastructure\Symfony\Controller\AbstractApiController;
use BaptisteContreras\SymfonyRequestParamBundle\Configuration\AutoProvideRequestDto;
use BaptisteContreras\SymfonyRequestParamBundle\Configuration\DtoRequestParam;
use BaptisteContreras\SymfonyRequestParamBundle\Model\SourceType;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/workers', name: 'workers_')]
#[OA\Tag(name: 'Workers')]
class WorkersController extends AbstractApiController
{
    #[OA\Response(
        response: 200,
        description: 'The list of all workers',
        content: new Model(
            type: JsonSearchAllWorkerInformationsViewModel::class
        )
    )]
    #[OA\Response(
        response: 500,
        description: 'Technical error',
        content: new OA\JsonContent(
            ref: '#/components/schemas/TechnicalError'
        )
    )]
    #[Route(path: '', name: 'list', methods: ['GET'])]
    public function listAll(SearchAllWorkerInformationsQueryHandler $searchAllWorkerInformationsQueryHandler): Response
    {
        // We only supports JSON content type for the moment.
        $presenter = SearchAllWorkerInformationsPresenter::json();

        $searchAllWorkerInformationsQueryHandler(SearchAllWorkerInformationsRequest::build(), $presenter);

        return $this->buildJsonResponse($presenter);
    }

    #[OA\RequestBody(
        description: 'The payload with all informations needed to join the ring',
        content: new Model(
            type: JoinRequest::class
        )
    )]
    #[OA\Response(
        response: 201,
        description: 'The worker successfully joined the ring',
        content: new Model(
            type: JsonSuccessViewModel::class
        )
    )]
    #[OA\Response(
        response: 400,
        description: 'Invalid Payload',
        content: new OA\JsonContent(
            ref: '#/components/schemas/ValidationErrorList'
        )
    )]
    #[OA\Response(
        response: 409,
        description: 'Conflicts with the current state of the server',
        content: new OA\JsonContent(
            ref: '#/components/schemas/BusinessError'
        )
    )]
    #[OA\Response(
        response: 500,
        description: 'Technical error',
        content: new OA\JsonContent(
            ref: '#/components/schemas/TechnicalError'
        )
    )]
    #[Route(path: '', name: 'join', methods: ['POST'])]
    #[AutoProvideRequestDto]
    public function join(
        #[DtoRequestParam(sourceType: SourceType::JSON, validateDto: false)] JoinRequest $joinWorkerNodeRequest,
        JoinCommandHandler $joinWorkerNodeCommandHandler
    ): Response {
        $presenter = JoinPresenter::json();

        $joinWorkerNodeCommandHandler($joinWorkerNodeRequest, $presenter);

        return $this->buildJsonResponse($presenter);
    }

    #[OA\PathParameter(
        description: 'The worker ID',
        name: 'workerId',
        example: 1
    )]
    #[OA\Response(
        response: 204,
        description: 'The worker successfully left the ring',
    )]
    #[OA\Response(
        response: 404,
        description: 'There is no worker for the given ID',
    )]
    #[OA\Response(
        response: 409,
        description: 'Conflicts with the current state of the server',
        content: new OA\JsonContent(
            ref: '#/components/schemas/BusinessError'
        )
    )]
    #[OA\Response(
        response: 500,
        description: 'Technical error',
        content: new OA\JsonContent(
            ref: '#/components/schemas/TechnicalError'
        )
    )]
    #[Route(path: '/{workerId}', name: 'leave', methods: ['DELETE'])]
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
