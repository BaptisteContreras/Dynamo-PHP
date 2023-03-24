<?php

namespace App\Manager\Application\Command\Worker\Leave\Presenter;

use App\Manager\Application\Command\Worker\Leave\Response\ErrorResponse;
use App\Manager\Application\Command\Worker\Leave\Response\LeaveResponse;
use App\Manager\Application\Command\Worker\Leave\Response\NotFoundResponse;
use App\Manager\Application\Command\Worker\Leave\Response\SuccessResponse;
use App\Manager\Application\Command\Worker\Leave\Response\ValidationErrorResponse;
use App\Manager\Application\Command\Worker\Leave\ViewModel\JsonLeaveViewModel;

class JsonPresenter extends LeavePresenter
{
    public function present(LeaveResponse $leftResponse): void
    {
        match (get_class($leftResponse)) {
            ValidationErrorResponse::class => $this->viewModel = JsonLeaveViewModel::validationError($leftResponse->getValidationErrors()),
            ErrorResponse::class => $this->viewModel = JsonLeaveViewModel::error($leftResponse->getError()),
            SuccessResponse::class => $this->viewModel = JsonLeaveViewModel::success(),
            NotFoundResponse::class => $this->viewModel = JsonLeaveViewModel::notFound(),
        };
    }
}
