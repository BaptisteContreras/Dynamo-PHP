<?php

namespace App\Manager\Application\Query\PreferenceList\Response;

use App\Manager\Domain\Model\Aggregate\PreferenceList\PreferenceList;
use App\Shared\Application\ApplicationResponseInterface;

abstract class PreferenceListResponse implements ApplicationResponseInterface
{
    public static function success(PreferenceList $preferenceList): self
    {
        return new SuccessResponse($preferenceList);
    }
}
