<?php

namespace App\Shared\Infrastructure\Persistence\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Symfony\Component\Uid\UuidV7;

/**
 * for the preference list, we need to store two array of UuidV7. These arrays are ordered, and we absolutely preserve that
 * order because this feature is built on it.
 * as mentioned here (https://www.doctrine-project.org/projects/doctrine-dbal/en/4.0/reference/types.html#json), json
 * type do not ensure that the order is kept.
 */
class OrderedUuidV7JsonArray extends Type
{
    final public const TYPE = 'ordered_uuidv7_json_array';

    private const STORAGE_STRUCT_INDEX_KEY = 'index';
    private const STORAGE_STRUCT_UUID_KEY = 'uuidv7';

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getJsonTypeDeclarationSQL($column);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): mixed
    {
        $arrayOfUuidToConvert = $value;

        if (null === $arrayOfUuidToConvert) {
            return null;
        }

        if (!is_array($arrayOfUuidToConvert)) {
            throw new \LogicException(sprintf('doctrine "%s" type except an array of %s as value', self::TYPE, UuidV7::class));
        }

        $dataToSerialize = [];

        foreach ($arrayOfUuidToConvert as $index => $uuid) {
            if (!is_numeric($index)) {
                throw new \LogicException(sprintf('only numeric keys can be used as index in a doctrine "%s" type', self::TYPE));
            }

            if (!$uuid instanceof UuidV7) {
                throw new \LogicException(sprintf('only %s can be stored in a doctrine "%s" type', UuidV7::class, self::TYPE));
            }

            $dataToSerialize[] = [
                self::STORAGE_STRUCT_INDEX_KEY => $index,
                self::STORAGE_STRUCT_UUID_KEY => $uuid,
            ];
        }

        return json_encode($dataToSerialize, JSON_THROW_ON_ERROR);
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

        /** @var array<int, array{"index": "string", "uuidv7": "string"}> $arrayOfUuidDecoded */
        $arrayOfUuidDecoded = json_decode($rawStringValue, true, 512, JSON_THROW_ON_ERROR);

        $arrayDecoded = [];

        foreach ($arrayOfUuidDecoded as $rawUuidEntry) {
            $arrayDecoded[(int) $rawUuidEntry[self::STORAGE_STRUCT_INDEX_KEY]] = UuidV7::fromString($rawUuidEntry[self::STORAGE_STRUCT_UUID_KEY]);
        }

        return $arrayDecoded;
    }

    public function getName(): string
    {
        return self::TYPE;
    }
}
