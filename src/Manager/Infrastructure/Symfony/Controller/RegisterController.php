<?php

namespace App\Manager\Infrastructure\Symfony\Controller;

use App\Manager\Application\Command\Register\RegisterRequest;
use App\Shared\Infrastructure\Symfony\Controller\AbstractApiController;
use BaptisteContreras\SymfonyRequestParamBundle\Configuration\AutoProvideRequestDto;
use BaptisteContreras\SymfonyRequestParamBundle\Configuration\DtoRequestParam;
use BaptisteContreras\SymfonyRequestParamBundle\Model\SourceType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/register', name: 'register_')]
class RegisterController extends AbstractApiController
{
    #[Route(path: '/', name: 'post')]
    #[AutoProvideRequestDto]
    public function register(#[DtoRequestParam(sourceType: SourceType::JSON)] RegisterRequest $registerRequest, Request $request): Response
    {
        dump($request->attributes);
        dump($registerRequest);

        return new JsonResponse();
    }
}
