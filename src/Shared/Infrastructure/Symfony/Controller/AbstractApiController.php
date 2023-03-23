<?php

namespace App\Shared\Infrastructure\Symfony\Controller;

use App\Shared\Application\ResponsePresenterInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;

abstract class AbstractApiController extends AbstractController
{
    public function __construct(protected readonly SerializerInterface $serializer)
    {
    }

    protected function buildJsonResponse(ResponsePresenterInterface $presenter): Response
    {
        return new JsonResponse(
            $this->serializer->serialize($presenter->toViewModel(), JsonEncoder::FORMAT),
            $presenter->getReturnCode()->value,
            [],
            true
        );
    }
}
