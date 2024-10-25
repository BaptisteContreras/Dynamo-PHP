<?php

namespace App\Foreground\Domain\Out\PreferenceList;

use App\Foreground\Domain\Model\Aggregate\PreferenceList\PreferenceList;

interface FinderInterface
{
    public function getPreferenceList(): PreferenceList;
}
