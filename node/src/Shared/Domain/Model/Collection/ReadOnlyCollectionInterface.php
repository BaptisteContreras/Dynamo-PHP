<?php

namespace App\Shared\Domain\Model\Collection;

/**
 * @template T
 *
 * @extends  \IteratorAggregate<string, T>
 */
interface ReadOnlyCollectionInterface extends \IteratorAggregate, \Countable
{
    /**
     * @return self<T>
     */
    public function asReadOnly(): self;

    /**
     * @return T|null
     */
    public function get(string $key): mixed;

    /**
     * @param T $element
     */
    public function exists(mixed $element): bool;

    public function keyExists(string $key): bool;

    public function isEmpty(): bool;

    /**
     * @return T|null
     */
    public function first(): mixed;

    /**
     * @return T|null
     */
    public function last(): mixed;

    /**
     * @return \Traversable<T>
     */
    public function getIterator(): \Traversable;

    /**
     * @param \Closure(T): bool $filterCallable
     */
    public function filter(callable $filterCallable): static;
}
