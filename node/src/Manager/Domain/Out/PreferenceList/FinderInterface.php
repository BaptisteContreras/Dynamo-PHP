<?php

namespace App\Manager\Domain\Out\PreferenceList;

use App\Manager\Domain\Model\Aggregate\PreferenceList\PreferenceList;

interface FinderInterface
{
    public function getPreferenceList(): PreferenceList;
}
