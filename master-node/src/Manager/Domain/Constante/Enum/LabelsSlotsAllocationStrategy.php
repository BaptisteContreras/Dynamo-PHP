<?php

namespace App\Manager\Domain\Constante\Enum;

use OpenApi\Attributes\Schema;

#[Schema]
enum LabelsSlotsAllocationStrategy: string
{
    case EQUAL_SIZE = 'equal-size';

    /**
     * @return array<string>
     */
    public static function getStrChoices(): array
    {
        return [
            self::EQUAL_SIZE->value,
        ];
    }
}
