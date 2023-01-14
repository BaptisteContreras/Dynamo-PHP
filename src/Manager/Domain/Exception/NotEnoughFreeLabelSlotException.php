<?php

namespace App\Manager\Domain\Exception;

class NotEnoughFreeLabelSlotException extends DomainException
{
    public function __construct(int $nbRequired, int $nbFound)
    {
        parent::__construct(sprintf(
            'Not enough free label found. %s are required but only %s are available.',
            $nbRequired,
            $nbFound
        ));
    }
}
