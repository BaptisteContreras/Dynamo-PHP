<?php

namespace App\Shared\Domain\Model\Collection;

/**
 * @template T
 *
 * @extends ReadOnlyCollectionInterface<T>
 */
interface MutableCollectionInterface extends ReadOnlyCollectionInterface
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

    /**
     * @param ReadOnlyCollectionInterface<T> $otherCollection
     * @param bool                           $keepExistingElement if true, do not override the local element if it exists in $otherCollection
     */
    public function merge(mixed $otherCollection, bool $keepExistingElement = true): static;
}
