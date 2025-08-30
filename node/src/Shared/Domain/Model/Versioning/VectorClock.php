<?php

namespace App\Shared\Domain\Model\Versioning;

class VectorClock
{
    /**
     * @param array<string, positive-int> $vector
     */
    public function __construct(
        private array $vector,
    ) {
    }

    public static function empty(): self
    {
        return new self([]);
    }

    public static function merge(self ...$clocks): self
    {
        $initVector = [];

        $vectorsToMerge = array_map(fn (VectorClock $clock) => $clock->getVector(), $clocks);

        foreach ($vectorsToMerge as $vector) {
            foreach ($vector as $node => $value) {
                if (!isset($initVector[$node])) {
                    $initVector[$node] = $value;

                    continue;
                }

                if ($value > $initVector[$node]) {
                    $initVector[$node] = $value;
                }
            }
        }

        return new self($initVector);
    }

    public function tick(string $node): self
    {
        if (!isset($this->vector[$node])) {
            $this->vector[$node] = 1;

            return $this;
        }

        ++$this->vector[$node];

        return $this;
    }

    public function compare(self $otherClock): ClockOrder
    {
        $greaterThanCounter = 0;
        $otherClockGreaterThanCounter = 0;

        $otherClockVector = $otherClock->getVector();

        foreach ($this->vector as $node => $value) {
            $otherClockValue = $otherClockVector[$node] ?? 0;

            if ($value > $otherClockValue) {
                ++$greaterThanCounter;
            }
        }

        foreach ($otherClockVector as $node => $otherClockValue) {
            $value = $this->vector[$node] ?? 0;

            if ($otherClockValue > $value) {
                ++$otherClockGreaterThanCounter;
            }
        }

        if (0 === $greaterThanCounter && 0 === $otherClockGreaterThanCounter) {
            return ClockOrder::IDENTICAL;
        }

        if ($greaterThanCounter > 0 && $otherClockGreaterThanCounter > 0) {
            return ClockOrder::CONCURRENT;
        }

        return $greaterThanCounter > $otherClockGreaterThanCounter ? ClockOrder::HAPPEN_AFTER : ClockOrder::HAPPEN_BEFORE;
    }

    /**
     * @return array<string, positive-int>
     */
    public function getVector(): array
    {
        return $this->vector;
    }
}
