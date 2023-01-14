<?php

namespace App\Manager\Application\Command\Register\Presenter;

use App\Manager\Application\Command\Register\Response\RegisterWorkerNodeErrorResponse;
use App\Manager\Application\Command\Register\Response\RegisterWorkerNodeResponse;
use App\Manager\Application\Command\Register\Response\RegisterWorkerNodeSuccessResponse;
use App\Manager\Application\Command\Register\Response\RegisterWorkerNodeValidationErrorResponse;
use App\Manager\Application\Command\Register\ViewModel\JsonRegisterWorkerNodeViewModel;
use App\Shared\Application\ViewModelInterface;
use App\Shared\Infrastructure\Http\HttpCode;

class JsonRegisterWorkerNodePresenter extends RegisterWorkerNodePresenter
{
    private JsonRegisterWorkerNodeViewModel $registerViewModel;

    public function present(RegisterWorkerNodeResponse $registerResponse): void
    {
        match (get_class($registerResponse)) {
            RegisterWorkerNodeValidationErrorResponse::class => $this->registerViewModel = JsonRegisterWorkerNodeViewModel::validationError($registerResponse->getValidationErrors()),
            RegisterWorkerNodeErrorResponse::class => $this->registerViewModel = JsonRegisterWorkerNodeViewModel::error($registerResponse->getError()),
            RegisterWorkerNodeSuccessResponse::class => $this->registerViewModel = JsonRegisterWorkerNodeViewModel::success($registerResponse->getWorkerNode()),
        };
    }

    public function toViewModel(): ViewModelInterface
    {
        return $this->registerViewModel;
    }

    public function getReturnCode(): HttpCode
    {
        return $this->registerViewModel->getCode();
    }
}
