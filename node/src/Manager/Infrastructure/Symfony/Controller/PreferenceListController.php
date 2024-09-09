<?php

namespace App\Manager\Infrastructure\Symfony\Controller;

use App\Manager\Application\Query\PreferenceList\PreferenceListQueryHandler;
use App\Manager\Application\Query\PreferenceList\Presenter\PreferenceListPresenter;
use App\Manager\Application\Query\PreferenceList\ViewModel\JsonSuccessViewModel;
use App\Shared\Infrastructure\Symfony\Controller\AbstractApiController;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[OA\Response(
    response: Response::HTTP_OK,
    description: 'Current state of the preference list',
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
#[Route(path: '/preference-list', name: 'preference_list', methods: ['GET'])]
class PreferenceListController extends AbstractApiController
{
    public function __invoke(PreferenceListQueryHandler $preferenceListQueryHandler): Response
    {
        $presenter = PreferenceListPresenter::json();

        $preferenceListQueryHandler($presenter);

        return $this->buildJsonResponse($presenter);
    }
}
