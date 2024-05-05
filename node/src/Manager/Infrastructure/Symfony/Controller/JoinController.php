<?php

namespace App\Manager\Infrastructure\Symfony\Controller;

use App\Manager\Application\Command\Join\JoinCommandHandler;
use App\Manager\Application\Command\Join\Presenter\JoinPresenter;
use App\Manager\Application\Command\Join\Request\JoinRequest;
use App\Manager\Application\Command\Join\ViewModel\JsonSuccessViewModel;
use App\Shared\Infrastructure\Symfony\Controller\AbstractApiController;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[OA\RequestBody(
    description: 'The payload with all informations needed to join the ring',
    content: new Model(
        type: JoinRequest::class
    )
)]
#[OA\Response(
    response: Response::HTTP_CREATED,
    description: 'The node successfully joined the ring',
    content: new Model(
        type: JsonSuccessViewModel::class
    )
)]
#[OA\Response(
    response: Response::HTTP_BAD_REQUEST,
    description: 'Bad request',
    content: new OA\JsonContent(
        ref: '#/components/schemas/BadRequestError'
    )
)]
#[OA\Response(
    response: Response::HTTP_UNPROCESSABLE_ENTITY,
    description: 'Invalid Payload',
    content: new OA\JsonContent(
        ref: '#/components/schemas/ValidationErrorList'
    )
)]
#[OA\Response(
    response: Response::HTTP_CONFLICT,
    description: 'Conflicts with the current state of the server',
    content: new OA\JsonContent(
        ref: '#/components/schemas/BusinessError'
    )
)]
#[OA\Response(
    response: Response::HTTP_INTERNAL_SERVER_ERROR,
    description: 'Technical error',
    content: new OA\JsonContent(
        ref: '#/components/schemas/TechnicalError'
    )
)]
#[Route(path: '/join', name: 'join', methods: ['POST'])]
class JoinController extends AbstractApiController
{
    public function __invoke(
        #[MapRequestPayload] JoinRequest $joinRequest,
        JoinCommandHandler $joinCommandHandler): Response
    {
        $presenter = JoinPresenter::json();

        $joinCommandHandler($joinRequest, $presenter);

        return $this->buildJsonResponse($presenter);
    }
}
