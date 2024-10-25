<?php

namespace App\Shared\Infrastructure\Symfony\Service;

use App\Shared\Domain\Out\RingKeyHasherInterface;
use Dynamophp\HashBundle\Service\DynamoHasherInterface;

readonly class CustomRingKeyHasher implements RingKeyHasherInterface
{
    public function __construct(private DynamoHasherInterface $dynamoHasher)
    {
    }

    /**
     * @return int<0, 360>
     */
    public function hash(string $key): int
    {
        return (int) $this->dynamoHasher->hash($key);
    }
}
