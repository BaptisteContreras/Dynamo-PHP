<?php

namespace App\Manager\Application\Command\LabelSlot\Init;

use App\Manager\Application\Command\LabelSlot\Init\Presenter\InitLabelSlotsPresenter;
use App\Manager\Application\Command\LabelSlot\Init\Response\InitLabelSlotsResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class InitCommandHandler
{
    public function __construct(
        private readonly ValidatorInterface $validator
    ) {
    }

    public function __invoke(InitRequest $initRequest, InitLabelSlotsPresenter $presenter): void
    {
        $validationErrors = $this->validator->validate($initRequest);

        if ($validationErrors->count() > 0) {
            $presenter->present(InitLabelSlotsResponse::withValidationError($validationErrors));

            return;
        }
    }
}
