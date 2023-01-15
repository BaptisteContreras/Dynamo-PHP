<?php

namespace App\Manager\Application\Command\Worker\Register\Presenter;

use App\Manager\Application\Command\Worker\Register\Response\ErrorResponse;
use App\Manager\Application\Command\Worker\Register\Response\RegisterWorkerNodeResponse;
use App\Manager\Application\Command\Worker\Register\Response\SuccessResponse;
use App\Manager\Application\Command\Worker\Register\Response\ValidationErrorResponse;
use App\Manager\Application\Command\Worker\Register\ViewModel\JsonRegisterWorkerNodeViewModel;

class JsonRegisterWorkerNodePresenter extends RegisterWorkerNodePresenter
{
    public function present(RegisterWorkerNodeResponse $registerResponse): void
    {
        match (get_class($registerResponse)) {
            ValidationErrorResponse::class => $this->viewModel = JsonRegisterWorkerNodeViewModel::validationError($registerResponse->getValidationErrors()),
            ErrorResponse::class => $this->viewModel = JsonRegisterWorkerNodeViewModel::error($registerResponse->getError()),
            SuccessResponse::class => $this->viewModel = JsonRegisterWorkerNodeViewModel::success($registerResponse->getWorkerNode()),
        };
    }
}
