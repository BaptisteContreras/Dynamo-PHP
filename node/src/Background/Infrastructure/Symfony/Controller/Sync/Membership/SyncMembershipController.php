<?php

namespace App\Background\Infrastructure\Symfony\Controller\Sync\Membership;

use App\Background\Application\Command\Sync\Membership\V1\Presenter\SyncMembershipPresenter;
use App\Background\Application\Command\Sync\Membership\V1\Request\SyncRequest;
use App\Background\Application\Command\Sync\Membership\V1\SyncMembershipV1CommandHandler;
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
#[Route(path: '/sync/membership/', name: 'sync_membership_', methods: ['POST'])]
class SyncMembershipController extends AbstractApiController
{
    #[Route(path: 'v1', name: 'v1', methods: ['POST'])]
    public function v1(
        #[MapRequestPayload] SyncRequest $syncRequest,
        SyncMembershipV1CommandHandler $syncMembershipV1CommandHandler
    ): Response {
        $presenter = SyncMembershipPresenter::json();

        $syncMembershipV1CommandHandler($syncRequest, $presenter);

        return $this->buildJsonResponse($presenter);
    }
}
