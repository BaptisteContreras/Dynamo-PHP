<?php

namespace App\Manager\Application\Query\PreferenceList\ViewModel;

use App\Manager\Domain\Model\Aggregate\PreferenceList\PreferenceList;
use App\Shared\Application\JsonViewModelInterface;
use App\Shared\Application\ViewModel;
use Symfony\Component\Serializer\Annotation\Ignore;

abstract class JsonPreferenceListViewModel extends ViewModel implements JsonViewModelInterface
{
    public static function success(PreferenceList $preferenceList): self
    {
        return new JsonSuccessViewModel($preferenceList);
    }

    #[Ignore]
    public function getCode(): int
    {
        return parent::getCode();
    }
}
