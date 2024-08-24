<?php

namespace App\Manager\Infrastructure\Symfony\Controller;

use App\Manager\Application\Query\Ring\Presenter\RingPresenter;
use App\Manager\Application\Query\Ring\RingQueryHandler;
use App\Shared\Infrastructure\Symfony\Controller\AbstractApiController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

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
