<?php

namespace App\Foreground\Infrastructure\Symfony\Controller\Public;

use App\Foreground\Application\Command\Public\Put\Presenter\PutDataPresenter;
use App\Foreground\Application\Command\Public\Put\PutDataCommandHandler;
use App\Foreground\Application\Command\Public\Put\Request\PutRequest;
use App\Shared\Infrastructure\Symfony\Controller\AbstractApiController;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[OA\RequestBody(
    description: 'The payload with all informations needed to put data',
    content: new Model(
        type: PutRequest::class
    )
)]
#[OA\Response(
    response: Response::HTTP_CREATED,
    description: 'Data are successfully put into the system',
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
#[Route(path: '/public/put', name: 'public_put', methods: ['POST'])]
class PutController extends AbstractApiController
{
    public function __invoke(
        #[MapRequestPayload] PutRequest $putDataRequest,
        PutDataCommandHandler $putDataCommandHandler,
    ): Response {
        $presenter = PutDataPresenter::json();

        $putDataCommandHandler($putDataRequest, $presenter);

        return $this->buildJsonResponse($presenter);
    }
}
