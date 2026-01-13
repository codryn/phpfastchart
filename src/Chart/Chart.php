<?php

declare(strict_types=1);

namespace Codryn\PHPFastChart\Chart;

use Codryn\PHPFastChart\Configuration\AxisClipMode;
use Codryn\PHPFastChart\Configuration\AxisConfiguration;
use Codryn\PHPFastChart\Configuration\ColorConfiguration;
use Codryn\PHPFastChart\Configuration\GridConfiguration;
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
    private ColorConfiguration $colorConfig;
    private GridConfiguration $gridConfig;
    private AxisConfiguration $axisConfig;
    private ?string $title = null;
    private ?string $xAxisLabel = null;
    private ?string $yAxisLabel = null;
    private bool $dataLabelsEnabled = false;

    /** @var array<DataSeries> */
    private array $dataSeries = [];

    public function __construct(
        private readonly ChartType $type,
    ) {
        $this->colorConfig = new ColorConfiguration();
        $this->gridConfig = new GridConfiguration();
        $this->axisConfig = new AxisConfiguration();
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
        $this->colorConfig = $this->colorConfig->withBackgroundColor($color);
        return $this;
    }

    /**
     * Set axis color.
     */
    public function setAxisColor(string $color): self
    {
        $this->colorConfig = $this->colorConfig->withAxisColor($color);
        return $this;
    }

    /**
     * Enable grid lines.
     */
    public function enableGrid(bool $enabled = true): self
    {
        $this->gridConfig = $this->gridConfig->withEnabled($enabled);
        return $this;
    }

    /**
     * Set grid line color.
     */
    public function setGridColor(string $color): self
    {
        $this->gridConfig = $this->gridConfig->withColor($color)->withEnabled(true);
        return $this;
    }

    /**
     * Set grid line spacing.
     */
    public function setGridSpacing(float $spacing): self
    {
        $this->gridConfig = $this->gridConfig->withSpacing($spacing);
        return $this;
    }

    /**
     * Set grid line width.
     */
    public function setGridLineWidth(float $width): self
    {
        $this->gridConfig = $this->gridConfig->withLineWidth($width);
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
     * Set X-axis range.
     */
    public function setXAxisRange(float $min, float $max): self
    {
        $this->axisConfig = $this->axisConfig->withXRange($min, $max);
        return $this;
    }

    /**
     * Set Y-axis range.
     */
    public function setYAxisRange(float $min, float $max): self
    {
        $this->axisConfig = $this->axisConfig->withYRange($min, $max);
        return $this;
    }

    /**
     * Set axis clip mode (Clip or Throw).
     */
    public function setAxisClipMode(AxisClipMode $mode): self
    {
        $this->axisConfig = $this->axisConfig->withClipMode($mode);
        return $this;
    }

    /**
     * Set chart title.
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Set X-axis label.
     */
    public function setXAxisLabel(string $label): self
    {
        $this->xAxisLabel = $label;
        return $this;
    }

    /**
     * Set Y-axis label.
     */
    public function setYAxisLabel(string $label): self
    {
        $this->yAxisLabel = $label;
        return $this;
    }

    /**
     * Enable data point labels.
     */
    public function enableDataLabels(bool $enabled = true): self
    {
        $this->dataLabelsEnabled = $enabled;
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
            $this->colorConfig,
            $this->gridConfig,
            $this->axisConfig,
            $this->title,
            $this->xAxisLabel,
            $this->yAxisLabel,
            $this->dataLabelsEnabled
        );
    }
}
