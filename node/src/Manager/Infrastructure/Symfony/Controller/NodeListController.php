<?php

namespace App\Manager\Infrastructure\Symfony\Controller;

use App\Manager\Application\Query\NodeList\NodeListQueryHandler;
use App\Manager\Application\Query\NodeList\Presenter\NodeListPresenter;
use App\Manager\Application\Query\NodeList\Request\NodeListRequest;
use App\Manager\Application\Query\NodeList\ViewModel\JsonSuccessViewModel;
use App\Shared\Infrastructure\Symfony\Controller\AbstractApiController;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/nodes', name: 'nodes_', methods: ['GET'])]
class NodeListController extends AbstractApiController
{
    /**
     * @param array<int>|null $states
     */
    #[OA\Response(
        response: Response::HTTP_OK,
        description: 'List of nodes',
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
    #[OA\Response(
        response: Response::HTTP_BAD_REQUEST,
        description: 'Bad request',
        content: new OA\JsonContent(
            ref: '#/components/schemas/BadRequestError'
        )
    )]
    #[OA\Parameter(
        name: 'seed',
        in: 'query',
        example: 'true'
    )]
    #[OA\Parameter(
        name: 'states[]',
        in: 'query',
        example: '[0, 1]'
    )]
    #[Route(path: '/', name: 'list', methods: ['GET'])]
    public function list(NodeListQueryHandler $nodeListQueryHandler, #[MapQueryParameter] ?bool $seed = null, #[MapQueryParameter] ?array $states = null): Response
    {
        $presenter = NodeListPresenter::json();

        $nodeListQueryHandler(NodeListRequest::guessFromParams($seed, $states), $presenter);

        return $this->buildJsonResponse($presenter);
    }

    //    #[Route(path: '/{uid}', name: 'details', methods: ['GET'])]
    //    public function details(NodeListQueryHandler $nodeListQueryHandler): Response
    //    {
    //        $presenter = NodeListPresenter::json();
    //
    //        $nodeListQueryHandler(NodeListRequest::noFilter(), $presenter);
    //
    //        return $this->buildJsonResponse($presenter);
    //    }
}
