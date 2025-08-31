<?php

namespace App\Shared\Infrastructure\Persistence\Type;

use App\Shared\Domain\Model\Versioning\VectorClock;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

/**
 * Here we only use TEXT column to store the vector clock because we'll never query inside it.
 * Also there is a limitation with UNIQUE CONSTRAINT on json column -> it can be used with B-TREE INDEX and I don't
 * see how to solve this issue due to Doctrine limitation.
 *
 * Second point but very important : we need to be able to search by vector clock. As we use TEXT column this means
 * that two identical vector cloack MUST have the same string representation once stored in the DB.
 *
 * This is natively not the case because ['node1' => 1, 'node2' => 2] is identical to ['node2' => 2, 'node1' => 1]
 *
 * So here we have to ensure an order in the raw vector. We'll sort this array by key, i.e., by node
 *
 * TODO add test for the sort
 */
class VectorClockJson extends Type
{
    final public const TYPE = 'vector_clock_json';

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getClobTypeDeclarationSQL($column);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): mixed
    {
        $vectorClock = $value;

        if (null === $vectorClock) {
            return null;
        }

        if (!$vectorClock instanceof VectorClock) {
            throw new \LogicException(sprintf('doctrine "%s" type except an array of %s as value', self::TYPE, VectorClock::class));
        }

        $sortedByNodeVector = $vectorClock->getVector();
        ksort($sortedByNodeVector);

        return json_encode($sortedByNodeVector, JSON_THROW_ON_ERROR);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): mixed
    {
        $rawStringValue = $value;

        if (null === $rawStringValue) {
            return null;
        }

        if (!is_string($rawStringValue) || !json_validate($rawStringValue)) {
            throw new \LogicException(sprintf('doctrine "%s" type except a valid json string value to decode', self::TYPE));
        }

        /** @var array<string, positive-int> $rawVector */
        $rawVector = json_decode($rawStringValue, true, 512, JSON_THROW_ON_ERROR);

        return new VectorClock($rawVector);
    }

    public function getName(): string
    {
        return self::TYPE;
    }
}
