<?php

namespace App\Shared\Domain\Model\Collection;

/**
 * @template T
 *
 * @implements MutableCollectionInterface<T>
 */
trait MutableCollection
{
    /**
     * @var array<string, T>
     */
    protected array $internal;

    /**
     * @var array<string>
     */
    protected array $removedElements = [];

    /**
     * @param T $value
     */
    abstract protected function getKeyFromElement(mixed $value): string;

    /**
     * @param T $value
     */
    public function add(mixed $value): static
    {
        $this->internal[$this->getKeyFromElement($value)] = $value;

        return $this;
    }

    /**
     * @param T $value
     */
    public function remove(mixed $value): static
    {
        $key = $this->getKeyFromElement($value);

        if (isset($this->internal[$key])) {
            unset($this->internal[$key]);
            $this->removedElements[] = $key;
        }

        return $this;
    }

    /**
     * @return array<string>
     */
    public function getRemovedElements(): array
    {
        return $this->removedElements;
    }
}
