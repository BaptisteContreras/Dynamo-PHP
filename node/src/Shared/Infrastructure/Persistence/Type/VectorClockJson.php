<?php

namespace App\Shared\Infrastructure\Persistence\Type;

use App\Shared\Domain\Model\Versioning\VectorClock;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Symfony\Component\Uid\UuidV7;

/**
 * for the preference list, we need to store two array of UuidV7. These arrays are ordered, and we absolutely preserve that
 * order because this feature is built on it.
 * as mentioned here (https://www.doctrine-project.org/projects/doctrine-dbal/en/4.0/reference/types.html#json), json
 * type do not ensure that the order is kept.
 */
class VectorClockJson extends Type
{
    final public const TYPE = 'vector_clock_json';

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getJsonTypeDeclarationSQL($column);
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

        return json_encode($vectorClock->getVector(), JSON_THROW_ON_ERROR);
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
