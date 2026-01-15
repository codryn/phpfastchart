<?php

declare(strict_types=1);

namespace Codryn\PHPFastChart\Util;

use Codryn\PHPFastChart\Exception\InvalidArgumentException;

/**
 * Input validation utility.
 */
final class Validator
{
    /**
     * Validate image dimension (width or height).
     *
     * @param int $dimension Dimension value in pixels
     * @return bool True if valid, false otherwise
     */
    public static function validateDimension(int $dimension): bool
    {
        return $dimension >= 50 && $dimension <= 4000;
    }

    /**
     * Validate color format.
     *
     * @param string $color Color string
     * @return bool True if valid, false otherwise
     */
    public static function validateColorFormat(string $color): bool
    {
        // Check hex format
        if (str_starts_with($color, '#')) {
            $hex = ltrim($color, '#');
            return (strlen($hex) === 6 || strlen($hex) === 3) && ctype_xdigit($hex);
        }

        // Check named colors
        $namedColors = [
            'red', 'green', 'blue', 'white', 'black', 'yellow',
            'cyan', 'magenta', 'gray', 'grey', 'orange', 'purple',
            'pink', 'brown', 'lime',
        ];

        return in_array(strtolower(trim($color)), $namedColors, true);
    }

    /**
     * Validate that a value is within a range.
     *
     * @param float $value Value to validate
     * @param float $min Minimum allowed value
     * @param float $max Maximum allowed value
     * @return bool True if valid, false otherwise
     */
    public static function validateRange(float $value, float $min, float $max): bool
    {
        return $value >= $min && $value <= $max;
    }

    /**
     * Validate range and throw exception if invalid.
     *
     * @param float $value Value to validate
     * @param float $min Minimum allowed value
     * @param float $max Maximum allowed value
     * @param string $fieldName Name of the field for error message
     * @throws InvalidArgumentException If value is out of range
     */
    public static function validateRangeOrThrow(
        float $value,
        float $min,
        float $max,
        string $fieldName
    ): void {
        if (!self::validateRange($value, $min, $max)) {
            throw new InvalidArgumentException(
                sprintf(
                    '%s must be between %s and %s, got %s',
                    $fieldName,
                    $min,
                    $max,
                    $value
                )
            );
        }
    }
}
