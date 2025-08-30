<?php

namespace App\Manager\Application\Command\Join\Presenter;

use App\Manager\Application\Command\Join\Response\JoinResponse;
use App\Manager\Application\Command\Join\Response\SuccessResponse;
use App\Manager\Application\Command\Join\ViewModel\JsonJoinViewModel;

class JsonPresenter extends JoinPresenter
{
    public function present(JoinResponse $joinResponse): void
    {
        match (get_class($joinResponse)) {
            SuccessResponse::class => $this->viewModel = JsonJoinViewModel::success($joinResponse->getNode()),
            default => throw new \LogicException('Unexpected value'),
        };
    }
}
