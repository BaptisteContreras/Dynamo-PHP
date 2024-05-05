<?php

namespace App\Manager\Domain\Service\VirtualNode\Attribution\Strategy;

use App\Manager\Domain\Exception\VirtualNodeAttributionFailedException;
use App\Manager\Domain\Service\VirtualNode\Attribution\RingSlotSelectionStrategyInterface;
use App\Shared\Domain\Const\RingInformations;

class RandomStrategy implements RingSlotSelectionStrategyInterface
{
    final public const string NAME = 'random';
    private const int MAX_TRY = 3;

    public function getName(): string
    {
        return self::NAME;
    }

    public function getVirtualNodes(string $label, int $numberOfVirtualNode): array
    {
        if ($numberOfVirtualNode > RingInformations::MAX_SLOTS) {
            throw new VirtualNodeAttributionFailedException(self::NAME);
        }

        /** @var array<int> $slots */
        $slots = [];

        for ($i = 0; $i < $numberOfVirtualNode; ++$i) {
            $nbTry = 0;
            do {
                $slot = $this->getSlot();
            } while (in_array($slot, $slots, true) && ++$nbTry <= self::MAX_TRY);

            if ($nbTry > self::MAX_TRY) {
                throw new VirtualNodeAttributionFailedException(self::NAME);
            }

            $slots[] = $slot;
        }

        return $slots;
    }

    private function getSlot(): int
    {
        return random_int(RingInformations::MIN_SLOTS, RingInformations::MAX_SLOTS);
    }
}
