<?php

namespace App\Manager\Domain\Exception;

use App\Shared\Domain\Exception\DomainException;

class UnknownVirtualNodeAttributionStrategyException extends DomainException
{
    public function __construct(string $name)
    {
        parent::__construct(sprintf('Virtual node attribution strategy "%s" does not exist', $name));
    }
}
