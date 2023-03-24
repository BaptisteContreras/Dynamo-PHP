<?php

namespace App\Manager\Application\Command\Ring\Reset;

use App\Manager\Application\Command\Ring\Reset\Presenter\ResetRingPresenter;
use App\Manager\Application\Command\Ring\Reset\Response\ResetRingResponse;
use App\Manager\Domain\Exception\DomainException;
use App\Manager\Domain\Service\Ring;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ResetRingCommandHandler
{
    public function __construct(
        private readonly ValidatorInterface $validator,
        private readonly Ring $ring
    ) {
    }

    public function __invoke(ResetRingPresenter $presenter): void
    {
        try {
            $this->ring->reset();
        } catch (DomainException $domainException) {
            $presenter->present(ResetRingResponse::withError($domainException));

            return;
        }

        $presenter->present(ResetRingResponse::success());
    }
}
