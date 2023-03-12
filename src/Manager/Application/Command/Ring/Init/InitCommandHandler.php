<?php

namespace App\Manager\Application\Command\Ring\Init;

use App\Manager\Application\Command\Ring\Init\Presenter\InitLabelSlotsPresenter;
use App\Manager\Application\Command\Ring\Init\Response\InitLabelSlotsResponse;
use App\Manager\Domain\Exception\DomainException;
use App\Manager\Domain\Service\Ring;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class InitCommandHandler
{
    public function __construct(
        private readonly ValidatorInterface $validator,
        private readonly Ring $ring
    ) {
    }

    public function __invoke(InitRequest $initRequest, InitLabelSlotsPresenter $presenter): void
    {
        $validationErrors = $this->validator->validate($initRequest);

        if ($validationErrors->count() > 0) {
            $presenter->present(InitLabelSlotsResponse::withValidationError($validationErrors));

            return;
        }

        try {
            $this->ring->init($initRequest->getAllocationStrategyNameEnum());
        } catch (DomainException $domainException) {
            $presenter->present(InitLabelSlotsResponse::withError($domainException));

            return;
        }

        $presenter->present(InitLabelSlotsResponse::success());
    }
}
