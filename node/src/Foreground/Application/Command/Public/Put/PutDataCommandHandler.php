<?php

namespace App\Foreground\Application\Command\Public\Put;

use App\Foreground\Application\Command\Public\Put\Presenter\PutDataPresenter;
use App\Foreground\Application\Command\Public\Put\Request\PutRequest;
use App\Foreground\Domain\Model\Aggregate\Item\Item;
use App\Foreground\Domain\Out\Node\FinderInterface as NodeFinderInterface;
use App\Foreground\Domain\Service\PutCoordinatorInterface;
use App\Foreground\Domain\Service\Version\VersionMarshaller;
use App\Shared\Domain\Out\RingKeyHasherInterface;

final readonly class PutDataCommandHandler
{
    public function __construct(
        private RingKeyHasherInterface $ringKeyHasher,
        private PutCoordinatorInterface $putCoordinator,
        private VersionMarshaller $versionMarshaller,
        private NodeFinderInterface $nodeFiner,
    ) {
    }

    public function __invoke(PutRequest $request, PutDataPresenter $presenter): void
    {
        $item = $this->convertRequestToDto($request);

        $nodeHandler = $this->putCoordinator->handleWrite($item);
        dd($nodeHandler);
    }

    private function convertRequestToDto(PutRequest $putRequest): Item
    {
        $requestItem = $putRequest->getItem();

        return Item::create(
            $requestItem->getKey(),
            $this->versionMarshaller->transformFromString($requestItem->getMetadata()->getVersion()),
            $this->ringKeyHasher->hash($requestItem->getKey()),
            $requestItem->getData(),
            $this->nodeFiner->getLocalEntry()->getId()
        );
    }
}
