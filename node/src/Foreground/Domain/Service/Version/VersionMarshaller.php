<?php

namespace App\Foreground\Domain\Service\Version;

use App\Shared\Domain\Model\Versioning\VectorClock;
use Symfony\Component\Serializer\SerializerInterface;

class VersionMarshaller
{
    // public function transformToString(AsyncVectorClock $vectorClock): string;
    public function __construct(private SerializerInterface $serializer)
    {
    }

    public function transformFromString(string $stringVersion): VectorClock
    {
        $rawTimestamps = json_decode(base64_decode($stringVersion), true, 512, JSON_THROW_ON_ERROR);

        dd($rawTimestamps);
    }
}
