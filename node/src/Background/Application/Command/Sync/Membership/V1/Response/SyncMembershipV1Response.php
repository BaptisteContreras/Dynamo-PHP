<?php

namespace App\Background\Application\Command\Sync\Membership\V1\Response;

use App\Shared\Application\ApplicationResponseInterface;

abstract class SyncMembershipV1Response implements ApplicationResponseInterface
{
    public static function success(): self
    {
        return new SuccessResponse();
    }
}
