<?php

declare(strict_types=1);

namespace Codryn\PHPFastChart\Configuration;

/**
 * Grid configuration for charts.
 *
 * Immutable value object for grid line settings.
 */
final readonly class GridConfiguration
{
    /**
     * Create a new grid configuration.
     *
     * @param bool        $enabled             Whether grid is enabled
     * @param bool        $showHorizontalLines Whether to show horizontal grid lines
     * @param bool        $showVerticalLines   Whether to show vertical grid lines
     * @param string      $color               Grid line color (hex format)
     * @param float       $lineWidth           Grid line width in pixels
     * @param float|null  $spacing             Grid spacing in data units (null for auto)
     */
    public function __construct(
        private bool $enabled = false,
        private bool $showHorizontalLines = true,
        private bool $showVerticalLines = true,
        private string $color = '#E0E0E0',
        private float $lineWidth = 1.0,
        private ?float $spacing = null,
    ) {
    }

    /**
     * Check if grid is enabled.
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * Check if horizontal grid lines should be shown.
     */
    public function showHorizontalLines(): bool
    {
        return $this->showHorizontalLines;
    }

    /**
     * Check if vertical grid lines should be shown.
     */
    public function showVerticalLines(): bool
    {
        return $this->showVerticalLines;
    }

    /**
     * Get grid line color.
     */
    public function getColor(): string
    {
        return $this->color;
    }

    /**
     * Get grid line width.
     */
    public function getLineWidth(): float
    {
        return $this->lineWidth;
    }

    /**
     * Get grid spacing (null for auto).
     */
    public function getSpacing(): ?float
    {
        return $this->spacing;
    }

    /**
     * Create a new instance with enabled state changed.
     */
    public function withEnabled(bool $enabled): self
    {
        return new self(
            $enabled,
            $this->showHorizontalLines,
            $this->showVerticalLines,
            $this->color,
            $this->lineWidth,
            $this->spacing,
        );
    }

    /**
     * Create a new instance with horizontal lines visibility changed.
     */
    public function withHorizontalLines(bool $show): self
    {
        return new self(
            $this->enabled,
            $show,
            $this->showVerticalLines,
            $this->color,
            $this->lineWidth,
            $this->spacing,
        );
    }

    /**
     * Create a new instance with vertical lines visibility changed.
     */
    public function withVerticalLines(bool $show): self
    {
        return new self(
            $this->enabled,
            $this->showHorizontalLines,
            $show,
            $this->color,
            $this->lineWidth,
            $this->spacing,
        );
    }

    /**
     * Create a new instance with color changed.
     */
    public function withColor(string $color): self
    {
        return new self(
            $this->enabled,
            $this->showHorizontalLines,
            $this->showVerticalLines,
            $color,
            $this->lineWidth,
            $this->spacing,
        );
    }

    /**
     * Create a new instance with line width changed.
     */
    public function withLineWidth(float $lineWidth): self
    {
        return new self(
            $this->enabled,
            $this->showHorizontalLines,
            $this->showVerticalLines,
            $this->color,
            $lineWidth,
            $this->spacing,
        );
    }

    /**
     * Create a new instance with spacing changed.
     */
    public function withSpacing(?float $spacing): self
    {
        return new self(
            $this->enabled,
            $this->showHorizontalLines,
            $this->showVerticalLines,
            $this->color,
            $this->lineWidth,
            $spacing,
        );
    }
}
