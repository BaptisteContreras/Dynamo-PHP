<?php

namespace App\Manager\Application\Command\Ring\Init\Presenter;

use App\Manager\Application\Command\Ring\Init\Response\ErrorResponse;
use App\Manager\Application\Command\Ring\Init\Response\InitLabelSlotsResponse;
use App\Manager\Application\Command\Ring\Init\Response\SuccessResponse;
use App\Manager\Application\Command\Ring\Init\Response\ValidationErrorResponse;
use App\Manager\Application\Command\Ring\Init\ViewModel\JsonInitLabelSlotsViewModel;

class JsonPresenter extends InitLabelSlotsPresenter
{
    public function present(InitLabelSlotsResponse $initLabelSlotsResponse): void
    {
        match (get_class($initLabelSlotsResponse)) {
            ValidationErrorResponse::class => $this->viewModel = JsonInitLabelSlotsViewModel::validationError($initLabelSlotsResponse->getValidationErrors()),
            SuccessResponse::class => $this->viewModel = JsonInitLabelSlotsViewModel::success(),
            ErrorResponse::class => $this->viewModel = JsonInitLabelSlotsViewModel::error($initLabelSlotsResponse->getError())
        };
    }
}
