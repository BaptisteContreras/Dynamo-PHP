<?php

namespace App\Manager\Infrastructure\Symfony\Controller;

use App\Manager\Application\Command\Register\Presenter\JsonRegisterWorkerNodePresenter;
use App\Manager\Application\Command\Register\RegisterWorkerNodeCommandHandler;
use App\Manager\Application\Command\Register\RegisterWorkerNodeRequest;
use App\Shared\Infrastructure\Symfony\Controller\AbstractApiController;
use BaptisteContreras\SymfonyRequestParamBundle\Configuration\AutoProvideRequestDto;
use BaptisteContreras\SymfonyRequestParamBundle\Configuration\DtoRequestParam;
use BaptisteContreras\SymfonyRequestParamBundle\Model\SourceType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/pool', name: 'pool_')]
class WorkerPoolController extends AbstractApiController
{
    #[Route(path: '/join', name: 'post')]
    #[AutoProvideRequestDto]
    public function register(
        #[DtoRequestParam(sourceType: SourceType::JSON, validateDto: false)] RegisterWorkerNodeRequest $registerRequest,
        RegisterWorkerNodeCommandHandler $registerCommandHandler
    ): Response {
        $presenter = new JsonRegisterWorkerNodePresenter();

        $registerCommandHandler($registerRequest, $presenter);

        return $this->buildJsonResponse($presenter);
    }
}
