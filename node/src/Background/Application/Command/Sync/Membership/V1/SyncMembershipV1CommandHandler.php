<?php

namespace App\Background\Application\Command\Sync\Membership\V1;

use App\Background\Application\Command\Sync\Membership\V1\Presenter\SyncMembershipPresenter;
use App\Background\Application\Command\Sync\Membership\V1\Request\SyncRequest;
use App\Background\Application\Command\Sync\Membership\V1\Response\SyncMembershipV1Response;

final readonly class SyncMembershipV1CommandHandler
{
    public function __invoke(SyncRequest $syncRequest, SyncMembershipPresenter $syncMembershipPresenter): void
    {
        $syncMembershipPresenter->present(SyncMembershipV1Response::success());
    }
}
