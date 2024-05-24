<?php

namespace App\Background\Application\Command\Sync\Membership\V1\ViewModel;

use Symfony\Component\HttpFoundation\Response;

class JsonSuccessViewModel extends JsonSyncMembershipV1ViewModel
{
    public function __construct()
    {
        parent::__construct(Response::HTTP_CREATED);
    }
}
