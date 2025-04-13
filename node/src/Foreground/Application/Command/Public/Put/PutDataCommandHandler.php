<?php

namespace App\Foreground\Application\Command\Public\Put;

use App\Foreground\Application\Command\Public\Put\Presenter\PutDataPresenter;
use App\Foreground\Application\Command\Public\Put\Request\PutRequest;
use App\Foreground\Domain\Model\Aggregate\Put\Item;
use App\Foreground\Domain\Service\PutCoordinatorInterface;
use App\Shared\Domain\Out\RingKeyHasherInterface;

final readonly class PutDataCommandHandler
{
    public function __construct(
        private RingKeyHasherInterface $ringKeyHasher,
        private PutCoordinatorInterface $putCoordinator
    ) {
    }

    public function __invoke(PutRequest $request, PutDataPresenter $presenter): void
    {
        $item = $this->convertRequestToDto($request);

        $nodeHandler = $this->putCoordinator->handleWrite($item);
        dd($nodeHandler);
        // check if current node support the keys
        // if not, forward it to preference list, until a node handle it

        // If current node can handle it :
        // - check the version
        // - handle local write
        // - propagate write to W replicas
    }

    private function convertRequestToDto(PutRequest $putRequest): Item
    {
        $requestItem = $putRequest->getItem();

        return Item::create(
            $requestItem->getKey(),
            $requestItem->getMetadata()->getVersion(),
            $this->ringKeyHasher->hash($requestItem->getKey()),
            $requestItem->getData()
        );
    }
}
