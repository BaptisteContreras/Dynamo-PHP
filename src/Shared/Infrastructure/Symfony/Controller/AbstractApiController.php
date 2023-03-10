<?php

namespace App\Shared\Infrastructure\Symfony\Controller;

use App\Shared\Application\ResponsePresenterInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractApiController extends AbstractController
{
    protected function buildJsonResponse(ResponsePresenterInterface $presenter): Response
    {
        return new JsonResponse($presenter->toViewModel(), $presenter->getReturnCode()->value);
    }
}
