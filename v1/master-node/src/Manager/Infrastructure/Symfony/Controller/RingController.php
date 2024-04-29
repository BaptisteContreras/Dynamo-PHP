<?php

namespace App\Manager\Infrastructure\Symfony\Controller;

use App\Manager\Application\Command\Ring\Init\InitCommandHandler;
use App\Manager\Application\Command\Ring\Init\InitRequest;
use App\Manager\Application\Command\Ring\Init\Presenter\InitLabelSlotsPresenter;
use App\Manager\Application\Command\Ring\Reset\Presenter\ResetRingPresenter;
use App\Manager\Application\Command\Ring\Reset\ResetRingCommandHandler;
use App\Shared\Infrastructure\Symfony\Controller\AbstractApiController;
use BaptisteContreras\SymfonyRequestParamBundle\Configuration\AutoProvideRequestDto;
use BaptisteContreras\SymfonyRequestParamBundle\Configuration\DtoRequestParam;
use BaptisteContreras\SymfonyRequestParamBundle\Model\SourceType;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use OpenApi\Attributes\Tag;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/ring', name: 'ring_')]
#[Tag(name: 'Ring')]
class RingController extends AbstractApiController
{
    #[OA\RequestBody(
        description: 'The payload with all informations needed to initialize the ring',
        content: new Model(
            type: InitRequest::class,
            groups: ['OA']
        )
    )]
    #[OA\Response(
        response: 204,
        description: 'The ring is initialized',
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
    #[Route(path: '', name: 'init', methods: ['POST'])]
    #[AutoProvideRequestDto]
    public function init(
        #[DtoRequestParam(sourceType: SourceType::JSON, validateDto: false)] InitRequest $initRequest,
        InitCommandHandler $initCommandHandler
    ): Response {
        $presenter = InitLabelSlotsPresenter::json();

        $initCommandHandler($initRequest, $presenter);

        return $this->buildJsonResponse($presenter);
    }

    #[OA\Response(
        response: 204,
        description: 'The ring is successfully reset',
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
    #[Route(path: '', name: 'reset', methods: ['DELETE'])]
    public function reset(
        ResetRingCommandHandler $resetRingCommandHandler
    ): Response {
        $presenter = ResetRingPresenter::json();

        $resetRingCommandHandler($presenter);

        return $this->buildJsonResponse($presenter);
    }
}
