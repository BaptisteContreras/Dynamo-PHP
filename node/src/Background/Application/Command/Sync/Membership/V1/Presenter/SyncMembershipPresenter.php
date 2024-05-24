<?php

namespace App\Background\Application\Command\Sync\Membership\V1\Presenter;

use App\Background\Application\Command\Sync\Membership\V1\Response\SyncMembershipV1Response;
use App\Shared\Application\ResponsePresenter;

abstract class SyncMembershipPresenter extends ResponsePresenter
{
    abstract public function present(SyncMembershipV1Response $syncMembershipV1Response): void;

    public static function json(): self
    {
        return new JsonSyncMembershipPresenter();
    }
}
