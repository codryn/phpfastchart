<?php

declare(strict_types=1);

namespace Codryn\PHPFastChart\Data;

/**
 * Represents a single data point with x and y coordinates.
 *
 * Immutable value object using readonly properties (PHP 8.1+).
 */
final class DataPoint
{
    /**
     * Create a new data point.
     *
     * @param float $x X-axis value
     * @param float $y Y-axis value
     * @param string|null $label Optional label for this point
     * @param string|null $color Optional color for this point (hex format like '#FF6384')
     */
    public function __construct(
        public readonly float $x,
        public readonly float $y,
        public readonly ?string $label = null,
        public readonly ?string $color = null,
    ) {
    }
}
