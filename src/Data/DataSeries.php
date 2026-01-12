<?php

declare(strict_types=1);

namespace Codryn\PHPFastChart\Data;

use Codryn\PHPFastChart\Exception\InvalidArgumentException;

/**
 * Represents a series of data points with styling.
 */
final class DataSeries
{
    /**
     * @param string $name Series name
     * @param array<DataPoint> $points Data points
     * @param string|null $lineColor Line color (hex or named)
     * @param string|null $fillColor Fill color for area charts
     */
    public function __construct(
        private readonly string $name,
        private readonly array $points,
        private readonly ?string $lineColor = null,
        private readonly ?string $fillColor = null,
    ) {
        if (count($this->points) === 0) {
            throw new InvalidArgumentException('DataSeries must contain at least one point');
        }
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return array<DataPoint>
     */
    public function getPoints(): array
    {
        return $this->points;
    }

    public function getLineColor(): ?string
    {
        return $this->lineColor;
    }

    public function getFillColor(): ?string
    {
        return $this->fillColor;
    }
}
