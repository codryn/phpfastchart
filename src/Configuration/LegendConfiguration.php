<?php

declare(strict_types=1);

namespace Codryn\PHPFastChart\Configuration;

/**
 * Immutable configuration for chart legend.
 *
 * Provides settings for legend visibility, position, and styling.
 */
final readonly class LegendConfiguration
{
    /**
     * @param bool $enabled Whether the legend is displayed
     * @param LegendPosition $position Position of the legend
     * @param int $fontSize Font size for legend text
     * @param string $textColor Color for legend text
     * @param string $backgroundColor Background color for legend box
     * @param string $borderColor Border color for legend box
     */
    public function __construct(
        private bool $enabled = false,
        private LegendPosition $position = LegendPosition::Right,
        private int $fontSize = 12,
        private string $textColor = '#333333',
        private string $backgroundColor = '#FFFFFF',
        private string $borderColor = '#CCCCCC'
    ) {
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function getPosition(): LegendPosition
    {
        return $this->position;
    }

    public function getFontSize(): int
    {
        return $this->fontSize;
    }

    public function getTextColor(): string
    {
        return $this->textColor;
    }

    public function getBackgroundColor(): string
    {
        return $this->backgroundColor;
    }

    public function getBorderColor(): string
    {
        return $this->borderColor;
    }

    public function withEnabled(bool $enabled): self
    {
        return new self(
            $enabled,
            $this->position,
            $this->fontSize,
            $this->textColor,
            $this->backgroundColor,
            $this->borderColor
        );
    }

    public function withPosition(LegendPosition $position): self
    {
        return new self(
            $this->enabled,
            $position,
            $this->fontSize,
            $this->textColor,
            $this->backgroundColor,
            $this->borderColor
        );
    }

    public function withFontSize(int $fontSize): self
    {
        return new self(
            $this->enabled,
            $this->position,
            $fontSize,
            $this->textColor,
            $this->backgroundColor,
            $this->borderColor
        );
    }

    public function withTextColor(string $textColor): self
    {
        return new self(
            $this->enabled,
            $this->position,
            $this->fontSize,
            $textColor,
            $this->backgroundColor,
            $this->borderColor
        );
    }

    public function withBackgroundColor(string $backgroundColor): self
    {
        return new self(
            $this->enabled,
            $this->position,
            $this->fontSize,
            $this->textColor,
            $backgroundColor,
            $this->borderColor
        );
    }

    public function withBorderColor(string $borderColor): self
    {
        return new self(
            $this->enabled,
            $this->position,
            $this->fontSize,
            $this->textColor,
            $this->backgroundColor,
            $borderColor
        );
    }
}
