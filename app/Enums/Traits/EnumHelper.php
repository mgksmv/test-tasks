<?php

namespace App\Enums\Traits;

trait EnumHelper
{
    public static function names(): array
    {
        return array_column(self::cases(), 'name');
    }

    public static function toObject(): array
    {
        $object = [];

        foreach (self::cases() as $case) {
            $object[$case->name] = $case->value;
        }

        return $object;
    }
}
