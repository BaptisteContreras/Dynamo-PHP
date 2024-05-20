<?php

namespace App\Shared\Domain\Model\Collection;

/**
 * @template T
 *
 * @implements \IteratorAggregate<string, T>
 */
abstract class ReadOnlyCollection implements \IteratorAggregate, \Countable
{
    /**
     * @var array<string, T>
     */
    protected array $internal;

    /**
     * @param array<string|int, T> $initialValues
     */
    public function __construct(array $initialValues = [])
    {
        $this->internal = [];

        /**
         * @var T $value
         */
        foreach ($initialValues as $value) {
            $this->internal[$this->getKeyFromElement($value)] = $value;
        }
    }

    /**
     * @param T $element
     */
    abstract protected function getKeyFromElement(mixed $element): string;

    /**
     * @return self<T>
     */
    abstract public function asReadOnly(): self;

    /**
     * @return T|null
     */
    public function get(string $key): mixed
    {
        return $this->internal[$key] ?? null;
    }

    public function exists(string $key): bool
    {
        return isset($this->internal[$key]);
    }

    /**
     * @return \Traversable<T>
     */
    public function getIterator(): \Traversable
    {
        yield from $this->internal;
    }

    public function count(): int
    {
        return count($this->internal);
    }
}
