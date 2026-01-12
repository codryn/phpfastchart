<?php

declare(strict_types=1);

namespace Codryn\PHPFastChart\Chart;

use Codryn\PHPFastChart\Configuration\ImageFormat;
use Codryn\PHPFastChart\Data\DataSeries;
use Codryn\PHPFastChart\Exception\InvalidArgumentException;
use Codryn\PHPFastChart\Renderer\SvgRenderer;
use Codryn\PHPFastChart\Util\Validator;

/**
 * Main chart class with fluent interface.
 */
final class Chart
{
    private int $width = 800;
    private int $height = 600;
    private ImageFormat $format = ImageFormat::SVG;
    private string $backgroundColor = '#FFFFFF';

    /** @var array<DataSeries> */
    private array $dataSeries = [];

    public function __construct(
        private readonly ChartType $type,
    ) {
    }

    /**
     * Set chart dimensions.
     *
     * @param int $width Width in pixels (50-4000)
     * @param int $height Height in pixels (50-4000)
     * @return self For method chaining
     * @throws InvalidArgumentException If dimensions are invalid
     */
    public function setSize(int $width, int $height): self
    {
        if (!Validator::validateDimension($width)) {
            throw new InvalidArgumentException("Width must be between 50 and 4000, got {$width}");
        }
        if (!Validator::validateDimension($height)) {
            throw new InvalidArgumentException("Height must be between 50 and 4000, got {$height}");
        }

        $this->width = $width;
        $this->height = $height;

        return $this;
    }

    /**
     * Set output format.
     */
    public function setFormat(ImageFormat $format): self
    {
        $this->format = $format;
        return $this;
    }

    /**
     * Set background color.
     */
    public function setBackgroundColor(string $color): self
    {
        $this->backgroundColor = $color;
        return $this;
    }

    /**
     * Add a data series to the chart.
     */
    public function addDataSeries(DataSeries $series): self
    {
        $this->dataSeries[] = $series;
        return $this;
    }

    /**
     * Generate chart and save to file.
     *
     * @param string $outputPath Output file path
     * @throws InvalidArgumentException If no data series added
     */
    public function generate(string $outputPath): void
    {
        $content = $this->render();

        $directory = dirname($outputPath);
        if (!is_dir($directory) && !mkdir($directory, 0777, true) && !is_dir($directory)) {
            throw new InvalidArgumentException("Failed to create directory: {$directory}");
        }

        file_put_contents($outputPath, $content);
    }

    /**
     * Render chart and return as string.
     *
     * @return string Chart content (SVG XML, or binary image data for PNG/WEBP)
     * @throws InvalidArgumentException If no data series added
     */
    public function render(): string
    {
        if (count($this->dataSeries) === 0) {
            throw new InvalidArgumentException('Chart must have at least one data series');
        }

        // For MVP, only SVG is implemented
        $renderer = new SvgRenderer($this->width, $this->height);

        return $renderer->render(
            $this->type,
            $this->dataSeries,
            $this->backgroundColor
        );
    }
}
