<?php

namespace App\Background\Application\Command\Sync\Membership\V1\Presenter;

use App\Background\Application\Command\Sync\Membership\V1\Response\SuccessResponse;
use App\Background\Application\Command\Sync\Membership\V1\Response\SyncMembershipV1Response;
use App\Background\Application\Command\Sync\Membership\V1\ViewModel\JsonSyncMembershipV1ViewModel;

class JsonSyncMembershipPresenter extends SyncMembershipPresenter
{
    public function present(SyncMembershipV1Response $syncMembershipV1Response): void
    {
        match (get_class($syncMembershipV1Response)) {
            SuccessResponse::class => $this->viewModel = JsonSyncMembershipV1ViewModel::success(),
            default => throw new \LogicException('Unexpected value')
        };
    }
}
