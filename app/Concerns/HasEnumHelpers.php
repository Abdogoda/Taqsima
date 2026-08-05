<?php

namespace App\Concerns;

trait HasEnumHelpers
{
    /**
     * Get an array of all enum values.
     *
     * @return array<int, string|int>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Get an array of all enum case names.
     *
     * @return array<int, string>
     */
    public static function names(): array
    {
        return array_column(self::cases(), 'name');
    }

    /**
     * Get an associative array of [Name => Value].
     *
     * @return array<string, string|int>
     */
    public static function toArray(): array
    {
        return array_combine(self::names(), self::values());
    }

    /**
     * Get an associative array of [Value => Name] suitable for select inputs.
     *
     * @return array<string|int, string>
     */
    public static function options(): array
    {
        return array_combine(self::values(), self::names());
    }

    /**
     * Get a random enum case.
     */
    public static function random(): static
    {
        $cases = self::cases();

        return $cases[array_rand($cases)];
    }

    /**
     * Check if a given value is a valid enum value.
     */
    public static function isValid(string|int $value): bool
    {
        return in_array($value, self::values(), true);
    }
}
