<?php

namespace App\Shared\Domain\Model\Collection;

/**
 * @template T
 */
interface MutableCollectionInterface
{
    /**
     * @param T $value
     */
    public function add(mixed $value): static;

    /**
     * @param T $value
     */
    public function remove(mixed $value): static;

    /**
     * @return array<string>
     */
    public function getRemovedElements(): array;
}
