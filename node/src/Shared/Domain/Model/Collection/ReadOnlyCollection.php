<?php

namespace App\Shared\Domain\Model\Collection;

/**
 * @template T
 *
 * @implements ReadOnlyCollectionInterface<T>
 *
 * @phpstan-consistent-constructor
 */
abstract class ReadOnlyCollection implements ReadOnlyCollectionInterface
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

    public static function createEmpty(): static
    {
        return new static();
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

    /**
     * @param T $element
     */
    public function exists(mixed $element): bool
    {
        return $this->keyExists($this->getKeyFromElement($element));
    }

    public function keyExists(string $key): bool
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

    public function isEmpty(): bool
    {
        return 0 === $this->count();
    }

    public function first(): mixed
    {
        $value = reset($this->internal);

        return false !== $value ? $value : null;
    }

    public function last(): mixed
    {
        $value = end($this->internal);

        return false !== $value ? $value : null;
    }

    public function filter(callable $filterCallable): static
    {
        return new static(
            array_filter($this->internal, $filterCallable)
        );
    }
}
