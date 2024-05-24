<?php

namespace App\Background\Application\Command\Sync\Membership\V1\ViewModel;

use App\Shared\Application\JsonViewModelInterface;
use App\Shared\Application\ViewModel;
use Symfony\Component\Serializer\Annotation\Ignore;

abstract class JsonSyncMembershipV1ViewModel extends ViewModel implements JsonViewModelInterface
{
    public static function success(): self
    {
        return new JsonSuccessViewModel();
    }

    #[Ignore]
    public function getCode(): int
    {
        return parent::getCode();
    }
}
