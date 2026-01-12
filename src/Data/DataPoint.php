<?php

declare(strict_types=1);

namespace Codryn\PHPFastChart\Data;

/**
 * Represents a single data point with x and y coordinates.
 *
 * Immutable value object using readonly properties (PHP 8.1+).
 */
final readonly class DataPoint
{
    /**
     * Create a new data point.
     *
     * @param float $x X-axis value
     * @param float $y Y-axis value
     * @param string|null $label Optional label for this point
     */
    public function __construct(
        public float $x,
        public float $y,
        public ?string $label = null,
    ) {
    }
}
