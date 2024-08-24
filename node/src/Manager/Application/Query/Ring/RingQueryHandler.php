<?php

namespace App\Manager\Application\Query\Ring;

use App\Manager\Application\Query\Ring\Presenter\RingPresenter;
use App\Manager\Domain\Out\Ring\FinderInterface;

final readonly class RingQueryHandler
{
    public function __construct(
        private FinderInterface $ringFinder,
    ) {
    }

    public function __invoke(RingPresenter $ringPresenter): void
    {
        $ring = $this->ringFinder->getLocalRing($ringPresenter);
        dd($ring->getSlots());
    }
}
