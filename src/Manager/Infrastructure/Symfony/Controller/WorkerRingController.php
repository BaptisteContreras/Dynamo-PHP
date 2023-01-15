<?php

namespace App\Manager\Infrastructure\Symfony\Controller;

use App\Manager\Application\Command\LabelSlot\Init\InitCommandHandler;
use App\Manager\Application\Command\LabelSlot\Init\InitRequest;
use App\Manager\Application\Command\LabelSlot\Init\Presenter\InitLabelSlotsPresenter;
use App\Manager\Application\Command\Worker\Register\Presenter\RegisterWorkerNodePresenter;
use App\Manager\Application\Command\Worker\Register\RegisterWorkerNodeCommandHandler;
use App\Manager\Application\Command\Worker\Register\RegisterWorkerNodeRequest;
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
        #[DtoRequestParam(sourceType: SourceType::JSON, validateDto: false)] RegisterWorkerNodeRequest $registerRequest,
        RegisterWorkerNodeCommandHandler $registerCommandHandler
    ): Response {
        $presenter = RegisterWorkerNodePresenter::json();

        $registerCommandHandler($registerRequest, $presenter);

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
