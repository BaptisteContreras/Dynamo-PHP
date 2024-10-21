<?php

namespace App\Foreground\Application\Command\Public\Put\Presenter;

use App\Foreground\Application\Command\Public\Put\Response\PutDataResponse;
use App\Foreground\Application\Command\Public\Put\Response\SuccessResponse;
use App\Foreground\Application\Command\Public\Put\ViewModel\JsonPutDataViewModel;

class JsonPutDataPresenter extends PutDataPresenter
{
    public function present(PutDataResponse $syncMembershipV1Response): void
    {
        match (get_class($syncMembershipV1Response)) {
            SuccessResponse::class => $this->viewModel = JsonPutDataViewModel::success(),
            default => throw new \LogicException('Unexpected value')
        };
    }
}
