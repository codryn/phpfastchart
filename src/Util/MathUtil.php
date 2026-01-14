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

    /**
     * Convert a data value to pixel coordinate.
     *
     * @param float $dataValue The value in data space
     * @param float $dataMin Minimum data value
     * @param float $dataMax Maximum data value
     * @param float $pixelMin Minimum pixel coordinate
     * @param float $pixelMax Maximum pixel coordinate
     * @return float Pixel coordinate
     */
    public static function dataToPixel(
        float $dataValue,
        float $dataMin,
        float $dataMax,
        float $pixelMin,
        float $pixelMax
    ): float {
        $dataRange = $dataMax - $dataMin;

        if ($dataRange === 0.0) {
            return ($pixelMin + $pixelMax) / 2;
        }

        $normalizedValue = ($dataValue - $dataMin) / $dataRange;
        return $pixelMin + $normalizedValue * ($pixelMax - $pixelMin);
    }

    /**
     * Convert a pixel coordinate to data value.
     *
     * @param float $pixelValue The pixel coordinate
     * @param float $dataMin Minimum data value
     * @param float $dataMax Maximum data value
     * @param float $pixelMin Minimum pixel coordinate
     * @param float $pixelMax Maximum pixel coordinate
     * @return float Data value
     */
    public static function pixelToData(
        float $pixelValue,
        float $dataMin,
        float $dataMax,
        float $pixelMin,
        float $pixelMax
    ): float {
        $pixelRange = $pixelMax - $pixelMin;

        if ($pixelRange === 0.0) {
            return ($dataMin + $dataMax) / 2;
        }

        $normalizedValue = ($pixelValue - $pixelMin) / $pixelRange;
        return $dataMin + $normalizedValue * ($dataMax - $dataMin);
    }

    /**
     * Calculate a "nice" number for axis scaling.
     *
     * Returns a rounded number suitable for axis labels (1, 2, 5 × 10^n).
     *
     * @param float $value The rough value to round
     * @return float A nice rounded number
     */
    public static function calculateNiceNumber(float $value): float
    {
        if ($value === 0.0) {
            return 0.0;
        }

        $absValue = abs($value);
        $magnitude = pow(10, floor(log10($absValue)));
        $normalized = $absValue / $magnitude;

        // Round to nice number: 1, 2, 5, or 10
        $niceNormalized = match (true) {
            $normalized <= 1.0 => 1.0,
            $normalized <= 2.0 => 2.0,
            $normalized <= 5.0 => 5.0,
            default => 10.0,
        };

        return $niceNormalized * $magnitude * ($value < 0 ? -1 : 1);
    }

    /**
     * Convert polar coordinates to Cartesian coordinates.
     *
     * @param float $angleDegrees Angle in degrees (0 = right, 90 = down, 180 = left, 270 = up)
     * @param float $radius Distance from center
     * @param float $centerX X coordinate of center point
     * @param float $centerY Y coordinate of center point
     * @return array{x: float, y: float} Cartesian coordinates
     */
    public static function polarToCartesian(
        float $angleDegrees,
        float $radius,
        float $centerX,
        float $centerY
    ): array {
        $angleRadians = deg2rad($angleDegrees);

        return [
            'x' => $centerX + ($radius * cos($angleRadians)),
            'y' => $centerY + ($radius * sin($angleRadians)),
        ];
    }
}
