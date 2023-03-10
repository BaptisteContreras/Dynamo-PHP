<?php

namespace App\Manager\Application\Command\Ring\Reset\Presenter;

use App\Manager\Application\Command\Ring\Reset\Response\ErrorResponse;
use App\Manager\Application\Command\Ring\Reset\Response\ResetRingResponse;
use App\Manager\Application\Command\Ring\Reset\Response\SuccessResponse;
use App\Manager\Application\Command\Ring\Reset\ViewModel\JsonResetRingViewModel;

class JsonPresenter extends ResetRingPresenter
{
    public function present(ResetRingResponse $resetRingResponse): void
    {
        match (get_class($resetRingResponse)) {
            SuccessResponse::class => $this->viewModel = JsonResetRingViewModel::success(),
            ErrorResponse::class => $this->viewModel = JsonResetRingViewModel::error($resetRingResponse->getError())
        };
    }
}
