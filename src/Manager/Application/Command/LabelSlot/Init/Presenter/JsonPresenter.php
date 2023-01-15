<?php

namespace App\Manager\Application\Command\LabelSlot\Init\Presenter;

use App\Manager\Application\Command\LabelSlot\Init\Response\InitLabelSlotsResponse;
use App\Manager\Application\Command\LabelSlot\Init\Response\ValidationErrorResponse;
use App\Manager\Application\Command\LabelSlot\Init\ViewModel\JsonInitLabelSlotsViewModel;

class JsonPresenter extends InitLabelSlotsPresenter
{
    public function present(InitLabelSlotsResponse $initLabelSlotsResponse): void
    {
        match (get_class($initLabelSlotsResponse)) {
            ValidationErrorResponse::class => $this->viewModel = JsonInitLabelSlotsViewModel::validationError($initLabelSlotsResponse->getValidationErrors())
        };
    }
}
