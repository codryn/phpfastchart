<?php

declare(strict_types=1);

namespace Codryn\PHPFastChart\Util;

use Codryn\PHPFastChart\Exception\InvalidArgumentException;

/**
 * Color parsing utility for converting color strings to RGBA values.
 */
final class ColorParser
{
    /**
     * Named color mappings (CSS basic colors).
     *
     * @var array<string, array{r: int, g: int, b: int}>
     */
    private const NAMED_COLORS = [
        'red' => ['r' => 255, 'g' => 0, 'b' => 0],
        'green' => ['r' => 0, 'g' => 128, 'b' => 0],
        'blue' => ['r' => 0, 'g' => 0, 'b' => 255],
        'white' => ['r' => 255, 'g' => 255, 'b' => 255],
        'black' => ['r' => 0, 'g' => 0, 'b' => 0],
        'yellow' => ['r' => 255, 'g' => 255, 'b' => 0],
        'cyan' => ['r' => 0, 'g' => 255, 'b' => 255],
        'magenta' => ['r' => 255, 'g' => 0, 'b' => 255],
        'gray' => ['r' => 128, 'g' => 128, 'b' => 128],
        'grey' => ['r' => 128, 'g' => 128, 'b' => 128],
        'orange' => ['r' => 255, 'g' => 165, 'b' => 0],
        'purple' => ['r' => 128, 'g' => 0, 'b' => 128],
        'pink' => ['r' => 255, 'g' => 192, 'b' => 203],
        'brown' => ['r' => 165, 'g' => 42, 'b' => 42],
        'lime' => ['r' => 0, 'g' => 255, 'b' => 0],
    ];

    /**
     * Parse a color string to RGBA components.
     *
     * @param string $color Color string (hex or named color)
     * @param float $alpha Alpha value (0.0 to 1.0)
     * @return array{r: int, g: int, b: int, a: float} RGBA components
     * @throws InvalidArgumentException If color format is invalid
     */
    public static function parse(string $color, float $alpha = 1.0): array
    {
        // Try hex format
        if (str_starts_with($color, '#')) {
            return self::parseHex($color, $alpha);
        }

        // Try named color
        $lowerColor = strtolower(trim($color));
        if (isset(self::NAMED_COLORS[$lowerColor])) {
            $rgb = self::NAMED_COLORS[$lowerColor];
            return ['r' => $rgb['r'], 'g' => $rgb['g'], 'b' => $rgb['b'], 'a' => $alpha];
        }

        throw new InvalidArgumentException(
            sprintf('Invalid color format: "%s". Use hex (#RRGGBB) or named colors.', $color)
        );
    }

    /**
     * Parse a hex color string.
     *
     * @param string $hex Hex color string (#RRGGBB or #RGB)
     * @param float $alpha Alpha value
     * @return array{r: int, g: int, b: int, a: float} RGBA components
     * @throws InvalidArgumentException If hex format is invalid
     */
    private static function parseHex(string $hex, float $alpha): array
    {
        $hex = ltrim($hex, '#');

        // Expand short hex (#RGB to #RRGGBB)
        if (strlen($hex) === 3) {
            $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
        }

        if (strlen($hex) !== 6 || !ctype_xdigit($hex)) {
            throw new InvalidArgumentException(
                sprintf('Invalid hex color format: "#%s". Expected #RRGGBB or #RGB.', $hex)
            );
        }

        return [
            'r' => (int) hexdec(substr($hex, 0, 2)),
            'g' => (int) hexdec(substr($hex, 2, 2)),
            'b' => (int) hexdec(substr($hex, 4, 2)),
            'a' => $alpha,
        ];
    }
}
