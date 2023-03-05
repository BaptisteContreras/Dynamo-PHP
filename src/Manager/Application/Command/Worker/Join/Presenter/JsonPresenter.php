<?php

namespace App\Manager\Application\Command\Worker\Join\Presenter;

use App\Manager\Application\Command\Worker\Join\Response\ErrorResponse;
use App\Manager\Application\Command\Worker\Join\Response\JoinResponse;
use App\Manager\Application\Command\Worker\Join\Response\SuccessResponse;
use App\Manager\Application\Command\Worker\Join\Response\ValidationErrorResponse;
use App\Manager\Application\Command\Worker\Join\ViewModel\JsonJoinViewModel;

class JsonPresenter extends JoinPresenter
{
    public function present(JoinResponse $registerResponse): void
    {
        match (get_class($registerResponse)) {
            ValidationErrorResponse::class => $this->viewModel = JsonJoinViewModel::validationError($registerResponse->getValidationErrors()),
            ErrorResponse::class => $this->viewModel = JsonJoinViewModel::error($registerResponse->getError()),
            SuccessResponse::class => $this->viewModel = JsonJoinViewModel::success($registerResponse->getWorkerNode()),
        };
    }
}
