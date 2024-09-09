<?php

namespace App\Manager\Application\Query\PreferenceList\Response;

use App\Manager\Domain\Model\Aggregate\PreferenceList\PreferenceList;

final class SuccessResponse extends PreferenceListResponse
{
    public function __construct(private PreferenceList $preferenceList)
    {
    }

    public function getPreferenceList(): PreferenceList
    {
        return $this->preferenceList;
    }
}
