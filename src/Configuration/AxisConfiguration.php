<?php

declare(strict_types=1);

namespace Codryn\PHPFastChart\Configuration;

use InvalidArgumentException;

/**
 * Configuration for axis scaling and range control.
 *
 * This immutable value object manages axis min/max values and clip mode.
 * If no range is specified, the chart will auto-scale to fit the data.
 */
final class AxisConfiguration
{
    /**
     * Create a new axis configuration.
     *
     * @param float|null $xMin X-axis minimum value (null for auto-scale)
     * @param float|null $xMax X-axis maximum value (null for auto-scale)
     * @param float|null $yMin Y-axis minimum value (null for auto-scale)
     * @param float|null $yMax Y-axis maximum value (null for auto-scale)
     * @param AxisClipMode $clipMode How to handle out-of-range data
     */
    public function __construct(
        private readonly ?float $xMin = null,
        private readonly ?float $xMax = null,
        private readonly ?float $yMin = null,
        private readonly ?float $yMax = null,
        private readonly AxisClipMode $clipMode = AxisClipMode::Throw
    ) {
    }

    public function getXMin(): ?float
    {
        return $this->xMin;
    }

    public function getXMax(): ?float
    {
        return $this->xMax;
    }

    public function getYMin(): ?float
    {
        return $this->yMin;
    }

    public function getYMax(): ?float
    {
        return $this->yMax;
    }

    public function getClipMode(): AxisClipMode
    {
        return $this->clipMode;
    }

    /**
     * Check if X-axis has an explicit range set.
     */
    public function hasXRange(): bool
    {
        return $this->xMin !== null && $this->xMax !== null;
    }

    /**
     * Check if Y-axis has an explicit range set.
     */
    public function hasYRange(): bool
    {
        return $this->yMin !== null && $this->yMax !== null;
    }

    /**
     * Create a new instance with the specified X-axis range.
     *
     * @throws InvalidArgumentException If min >= max
     */
    public function withXRange(float $xMin, float $xMax): self
    {
        if ($xMin >= $xMax) {
            throw new InvalidArgumentException('X-axis minimum must be less than maximum');
        }

        return new self($xMin, $xMax, $this->yMin, $this->yMax, $this->clipMode);
    }

    /**
     * Create a new instance with the specified Y-axis range.
     *
     * @throws InvalidArgumentException If min >= max
     */
    public function withYRange(float $yMin, float $yMax): self
    {
        if ($yMin >= $yMax) {
            throw new InvalidArgumentException('Y-axis minimum must be less than maximum');
        }

        return new self($this->xMin, $this->xMax, $yMin, $yMax, $this->clipMode);
    }

    /**
     * Create a new instance with the specified clip mode.
     */
    public function withClipMode(AxisClipMode $clipMode): self
    {
        return new self($this->xMin, $this->xMax, $this->yMin, $this->yMax, $clipMode);
    }
}
