<?php

namespace App\Manager\Application\Query\NodeList\Request;

abstract class NodeListRequest
{
    public static function noFilter(): self
    {
        return new NoFilterRequest();
    }

    /**
     * @param array<int>|null $state
     */
    public static function filtered(?bool $seed, ?array $state): self
    {
        return new FilteredRequest($seed, $state);
    }

    /**
     * @param array<int>|null $states
     */
    public static function guessFromParams(?bool $seed, ?array $states): self
    {
        if (null === $seed && null === $states) {
            return self::noFilter();
        }

        return self::filtered($seed, $states);
    }
}
