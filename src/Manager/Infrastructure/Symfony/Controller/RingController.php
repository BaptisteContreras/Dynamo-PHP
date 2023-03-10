<?php

namespace App\Manager\Infrastructure\Symfony\Controller;

use App\Manager\Application\Command\LabelSlot\Init\InitCommandHandler;
use App\Manager\Application\Command\LabelSlot\Init\InitRequest;
use App\Manager\Application\Command\LabelSlot\Init\Presenter\InitLabelSlotsPresenter;
use App\Manager\Application\Command\Ring\Reset\Presenter\ResetRingPresenter;
use App\Manager\Application\Command\Ring\Reset\ResetRingCommandHandler;
use App\Shared\Infrastructure\Symfony\Controller\AbstractApiController;
use BaptisteContreras\SymfonyRequestParamBundle\Configuration\AutoProvideRequestDto;
use BaptisteContreras\SymfonyRequestParamBundle\Configuration\DtoRequestParam;
use BaptisteContreras\SymfonyRequestParamBundle\Model\SourceType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/ring', name: 'ring_')]
class RingController extends AbstractApiController
{
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

    #[Route(path: '/reset', name: 'reset', methods: ['DELETE'])]
    public function reset(
        ResetRingCommandHandler $resetRingCommandHandler
    ): Response {
        $presenter = ResetRingPresenter::json();

        $resetRingCommandHandler($presenter);

        return $this->buildJsonResponse($presenter);
    }
}
