<?php

namespace App\Enums\Traits;

trait EnumTrait
{
    public static function getValues(): array
    {
        return collect(self::cases())->map(fn($field) => $field->value)->toArray();
    }

    public static function getKeys(): array
    {
        return collect(self::cases())->map(fn($field) => $field->name)->toArray();
    }

    public static function random(): mixed
    {
        return collect(self::cases())->random();
    }

    public static function asObject(): \stdClass
    {
        $cases = self::cases();
        $obj = new \stdClass();
        foreach ($cases as $case) {
            $key = $case->name;
            $obj->$key = $case->value;
        }
        return $obj;
    }

    public static function implode($separator = ','): string
    {
        return implode($separator, self::getValues());
    }
}
