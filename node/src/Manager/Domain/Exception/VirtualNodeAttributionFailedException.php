<?php

namespace App\Manager\Domain\Exception;

use App\Shared\Domain\Exception\DomainException;

class VirtualNodeAttributionFailedException extends DomainException
{
    public function __construct(string $strategy)
    {
        parent::__construct(sprintf('The strategy %s failed to attribute virtual nodes', $strategy));
    }
}
