<?php

declare(strict_types=1);

namespace Codryn\PHPFastChart\Configuration;

/**
 * Color configuration for charts.
 *
 * Immutable value object using readonly properties.
 */
final class ColorConfiguration
{
    /**
     * Create a new color configuration.
     *
     * @param string $backgroundColor Background color (hex or named)
     * @param string $axisColor Axis color (hex or named)
     */
    public function __construct(
        private readonly string $backgroundColor = '#FFFFFF',
        private readonly string $axisColor = '#333333',
    ) {
    }

    public function getBackgroundColor(): string
    {
        return $this->backgroundColor;
    }

    public function getAxisColor(): string
    {
        return $this->axisColor;
    }

    /**
     * Create a new configuration with a different background color.
     */
    public function withBackgroundColor(string $color): self
    {
        return new self($color, $this->axisColor);
    }

    /**
     * Create a new configuration with a different axis color.
     */
    public function withAxisColor(string $color): self
    {
        return new self($this->backgroundColor, $color);
    }
}
