<?php

namespace App\Manager\Application\Query\Worker\Search\Request;

final class SearchAllWorkerInformationsRequest
{
    public static function build(): self
    {
        return new self();
    }
}
