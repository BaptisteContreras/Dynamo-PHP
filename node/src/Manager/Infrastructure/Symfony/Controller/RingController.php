<?php

namespace App\Manager\Infrastructure\Symfony\Controller;

use App\Manager\Application\Query\Ring\Presenter\RingPresenter;
use App\Manager\Application\Query\Ring\RingQueryHandler;
use App\Manager\Application\Query\Ring\ViewModel\JsonSuccessViewModel;
use App\Shared\Infrastructure\Symfony\Controller\AbstractApiController;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[OA\Response(
    response: Response::HTTP_OK,
    description: 'The node successfully joined the ring',
    content: new Model(
        type: JsonSuccessViewModel::class
    )
)]
#[OA\Response(
    response: Response::HTTP_INTERNAL_SERVER_ERROR,
    description: 'Technical error',
    content: new OA\JsonContent(
        ref: '#/components/schemas/TechnicalError'
    )
)]
#[Route(path: '/ring', name: 'ring', methods: ['GET'])]
class RingController extends AbstractApiController
{
    public function __invoke(RingQueryHandler $ringQueryHandler): Response
    {
        $presenter = RingPresenter::json();

        $ringQueryHandler($presenter);

        return $this->buildJsonResponse($presenter);
    }
}
