<?php

namespace App\Manager\Application\Command\Ring\Reset\ViewModel;

use App\Manager\Domain\Exception\DomainException;
use App\Shared\Application\JsonViewModelInterface;
use App\Shared\Application\ViewModel;

abstract class JsonResetRingViewModel extends ViewModel implements JsonViewModelInterface
{
    public static function success(): self
    {
        return new JsonSuccessViewModel();
    }

    public static function error(DomainException $domainException): self
    {
        return new JsonErrorViewModel($domainException);
    }
}
