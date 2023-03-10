<?php

namespace App\Manager\Infrastructure\Symfony\Controller;

use App\Manager\Application\Command\LabelSlot\Init\InitCommandHandler;
use App\Manager\Application\Command\LabelSlot\Init\InitRequest;
use App\Manager\Application\Command\LabelSlot\Init\Presenter\InitLabelSlotsPresenter;
use App\Manager\Application\Command\Worker\Join\JoinCommandHandler;
use App\Manager\Application\Command\Worker\Join\JoinRequest;
use App\Manager\Application\Command\Worker\Join\Presenter\JoinPresenter;
use App\Manager\Application\Command\Worker\Leave\LeaveCommandHandler;
use App\Manager\Application\Command\Worker\Leave\LeaveRequest;
use App\Manager\Application\Command\Worker\Leave\Presenter\LeavePresenter;
use App\Shared\Infrastructure\Symfony\Controller\AbstractApiController;
use BaptisteContreras\SymfonyRequestParamBundle\Configuration\AutoProvideRequestDto;
use BaptisteContreras\SymfonyRequestParamBundle\Configuration\DtoRequestParam;
use BaptisteContreras\SymfonyRequestParamBundle\Model\SourceType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/ring', name: 'ring_')]
class WorkerRingController extends AbstractApiController
{
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

    #[Route(path: '/leave/{workerId}', name: 'leave', methods: ['DELETE'])]
    #[AutoProvideRequestDto]
    public function leave(
        int $workerId,
        LeaveCommandHandler $leftCommandHandler
    ): Response {
        $presenter = LeavePresenter::json();

        $leftCommandHandler(LeaveRequest::build($workerId), $presenter);

        return $this->buildJsonResponse($presenter);
    }

    #[Route(path: '/slots/init', name: 'slots_init', methods: ['POST'])]
    #[AutoProvideRequestDto]
    public function init(
        #[DtoRequestParam(sourceType: SourceType::JSON, validateDto: false)] InitRequest $initRequest,
        InitCommandHandler $initCommandHandler
    ): Response {
        $presenter = InitLabelSlotsPresenter::json();

        $initCommandHandler($initRequest, $presenter);

        return $this->buildJsonResponse($presenter);
    }
}
