<?php

namespace App\Background\Domain\Out\PreferenceList;

use App\Background\Domain\Model\Aggregate\PreferenceList\PreferenceList;

interface UpdaterInterface
{
    public function savePreferenceList(PreferenceList $preferenceList): void;
}
