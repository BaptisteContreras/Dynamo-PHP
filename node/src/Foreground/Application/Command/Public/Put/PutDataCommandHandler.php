<?php

namespace App\Foreground\Application\Command\Public\Put;

use App\Foreground\Application\Command\Public\Put\Presenter\PutDataPresenter;
use App\Foreground\Application\Command\Public\Put\Request\PutRequest;
use App\Foreground\Domain\Service\PutCoordinatorInterface;

final readonly class PutDataCommandHandler
{
    public function __construct(
        private PutCoordinatorInterface $putCoordinator
    ) {
    }

    public function __invoke(PutRequest $request, PutDataPresenter $presenter): void
    {
        dd($this->putCoordinator->isLocalNodeOwnerOf(0));
        // check if current node support the keys
        // if not, forward it to preference list, until a node handle it

        // If current node can handle it :
        // - check the version
        // - handle local write
        // - propagate write to W replicas
    }
}
