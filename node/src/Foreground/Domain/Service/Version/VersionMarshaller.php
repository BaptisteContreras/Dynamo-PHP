<?php

namespace App\Foreground\Domain\Service\Version;

use App\Shared\Domain\Model\Versioning\VectorClock;

class VersionMarshaller
{
    /**
     * @param VectorClock|array<VectorClock> $version
     */
    public function transformToString(VectorClock|array $version): string
    {
        if ($version instanceof VectorClock) {
            $version = [$version];
        }

        return base64_encode(json_encode(array_map(fn (VectorClock $vectorClock) => $vectorClock->getVector(), $version), JSON_THROW_ON_ERROR));
    }

    public function transformFromString(string $stringVersion): VectorClock
    {
        /** @var array<int, array<string, positive-int>> $rawVectors */
        $rawVectors = json_decode(base64_decode($stringVersion), true, 512, JSON_THROW_ON_ERROR);

        $clocks = array_map(fn (array $vector) => new VectorClock($vector), $rawVectors);

        // TODO a little bit of validation ?

        return VectorClock::merge(...$clocks);
    }
}
