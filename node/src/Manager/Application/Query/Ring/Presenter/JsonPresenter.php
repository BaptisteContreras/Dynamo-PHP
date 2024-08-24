<?php

namespace App\Manager\Application\Query\Ring\Presenter;

use App\Manager\Application\Query\Ring\Response\RingResponse;
use App\Manager\Application\Query\Ring\Response\SuccessResponse;
use App\Manager\Application\Query\Ring\ViewModel\JsonRingViewModel;

class JsonPresenter extends RingPresenter
{
    public function present(RingResponse $joinResponse): void
    {
        match (get_class($joinResponse)) {
            SuccessResponse::class => $this->viewModel = JsonRingViewModel::success($joinResponse->getNode()),
            default => throw new \LogicException('Unexpected value')
        };
    }
}
