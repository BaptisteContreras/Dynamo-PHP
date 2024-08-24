<?php

namespace App\Manager\Application\Query\Ring\Presenter;

use App\Manager\Application\Query\Ring\Response\RingResponse;
use App\Manager\Application\Query\Ring\Response\SuccessResponse;
use App\Manager\Application\Query\Ring\ViewModel\JsonRingViewModel;

class JsonPresenter extends RingPresenter
{
    public function present(RingResponse $ringResponse): void
    {
        match (get_class($ringResponse)) {
            SuccessResponse::class => $this->viewModel = JsonRingViewModel::success($ringResponse->getRing()),
            default => throw new \LogicException('Unexpected value')
        };
    }
}
