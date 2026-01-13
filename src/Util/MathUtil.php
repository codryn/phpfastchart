<?php

declare(strict_types=1);

namespace Codryn\PHPFastChart\Util;

/**
 * Mathematical utility functions for chart calculations.
 */
final class MathUtil
{
    /**
     * Calculate appropriate grid spacing for the given range.
     *
     * This function calculates a "nice" grid spacing value that produces
     * readable, evenly-spaced grid lines across the chart.
     *
     * @param float $min Minimum value in the range
     * @param float $max Maximum value in the range
     * @param int $pixelSize Available pixel space for the axis
     * @return float Grid spacing in data units
     */
    public static function calculateGridSpacing(float $min, float $max, int $pixelSize): float
    {
        $range = abs($max - $min);

        if ($range === 0.0) {
            return 10.0;
        }

        // Target 5-10 grid lines, aim for ~50 pixels between lines
        $targetLineCount = max(5, min(10, (int) ($pixelSize / 50)));
        $roughSpacing = $range / $targetLineCount;

        // Calculate the magnitude (power of 10)
        $magnitude = pow(10, floor(log10($roughSpacing)));

        // Normalize to range [1, 10)
        $normalized = $roughSpacing / $magnitude;

        // Choose nice number: 1, 2, 5, or 10
        $niceNormalized = match (true) {
            $normalized <= 1.0 => 1.0,
            $normalized <= 2.0 => 2.0,
            $normalized <= 5.0 => 5.0,
            default => 10.0,
        };

        return $niceNormalized * $magnitude;
    }
}
