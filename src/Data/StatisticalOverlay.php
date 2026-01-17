<?php

declare(strict_types=1);

namespace Codryn\PHPFastChart\Data;

use Codryn\PHPFastChart\Exception\InvalidArgumentException;

/**
 * Represents statistical overlay data to be rendered over a chart.
 *
 * Contains min, max, average, and standard deviation values to be displayed
 * as vertical lines with labels.
 */
final class StatisticalOverlay
{
    /**
     * Create a new statistical overlay.
     *
     * @param float $min Minimum value
     * @param float $max Maximum value
     * @param float $average Average value
     * @param float $stdDeviation Standard deviation
     * @param string $color Color for the overlay lines and labels (hex format like '#FF0000')
     * @throws InvalidArgumentException If min > max or stdDeviation is negative
     */
    public function __construct(
        private readonly float $min,
        private readonly float $max,
        private readonly float $average,
        private readonly float $stdDeviation,
        private readonly string $color = '#FF0000',
    ) {
        if ($this->min > $this->max) {
            throw new InvalidArgumentException("Minimum value ({$this->min}) cannot be greater than maximum value ({$this->max})");
        }
        if ($this->stdDeviation < 0) {
            throw new InvalidArgumentException("Standard deviation cannot be negative, got {$this->stdDeviation}");
        }
    }

    public function getMin(): float
    {
        return $this->min;
    }

    public function getMax(): float
    {
        return $this->max;
    }

    public function getAverage(): float
    {
        return $this->average;
    }

    public function getStdDeviation(): float
    {
        return $this->stdDeviation;
    }

    public function getColor(): string
    {
        return $this->color;
    }
}
