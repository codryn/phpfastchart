<?php

declare(strict_types=1);

namespace Codryn\PHPFastChart\Chart;

use Codryn\PHPFastChart\Configuration\AxisClipMode;
use Codryn\PHPFastChart\Configuration\AxisConfiguration;
use Codryn\PHPFastChart\Configuration\ColorConfiguration;
use Codryn\PHPFastChart\Configuration\GridConfiguration;
use Codryn\PHPFastChart\Configuration\ImageFormat;
use Codryn\PHPFastChart\Configuration\LegendConfiguration;
use Codryn\PHPFastChart\Configuration\LegendPosition;
use Codryn\PHPFastChart\Data\DataSeries;
use Codryn\PHPFastChart\Data\StatisticalOverlay;
use Codryn\PHPFastChart\Exception\InvalidArgumentException;
use Codryn\PHPFastChart\Renderer\RasterRenderer;
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
    private LegendConfiguration $legendConfig;
    private ?string $title = null;
    private ?string $xAxisLabel = null;
    private ?string $yAxisLabel = null;
    private bool $dataLabelsEnabled = false;

    /** @var array<DataSeries> */
    private array $dataSeries = [];

    /** @var array<StatisticalOverlay> */
    private array $statisticalOverlays = [];

    public function __construct(
        private readonly ChartType $type,
    ) {
        $this->colorConfig = new ColorConfiguration();
        $this->gridConfig = new GridConfiguration();
        $this->axisConfig = new AxisConfiguration();
        $this->legendConfig = new LegendConfiguration();
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
     * Enable legend display.
     *
     * @return self For method chaining
     */
    public function enableLegend(): self
    {
        $this->legendConfig = $this->legendConfig->withEnabled(true);
        return $this;
    }

    /**
     * Disable legend display.
     *
     * @return self For method chaining
     */
    public function disableLegend(): self
    {
        $this->legendConfig = $this->legendConfig->withEnabled(false);
        return $this;
    }

    /**
     * Set legend position.
     *
     * @param LegendPosition $position Position for the legend
     * @return self For method chaining
     */
    public function setLegendPosition(LegendPosition $position): self
    {
        $this->legendConfig = $this->legendConfig->withPosition($position);
        return $this;
    }

    /**
     * Set legend text color.
     *
     * @param string $color Color value
     * @return self For method chaining
     */
    public function setLegendTextColor(string $color): self
    {
        $this->legendConfig = $this->legendConfig->withTextColor($color);
        return $this;
    }

    /**
     * Set legend background color.
     *
     * @param string $color Color value
     * @return self For method chaining
     */
    public function setLegendBackgroundColor(string $color): self
    {
        $this->legendConfig = $this->legendConfig->withBackgroundColor($color);
        return $this;
    }

    /**
     * Set legend border color.
     *
     * @param string $color Color value
     * @return self For method chaining
     */
    public function setLegendBorderColor(string $color): self
    {
        $this->legendConfig = $this->legendConfig->withBorderColor($color);
        return $this;
    }

    /**
     * Add a statistical overlay to the chart.
     *
     * Statistical overlays display min, max, average, and standard deviation
     * as vertical lines with labels over the chart data.
     *
     * @param StatisticalOverlay $overlay Statistical overlay to add
     * @return self For method chaining
     * @throws InvalidArgumentException If chart type doesn't support overlays (Pie, Radar)
     */
    public function addStatisticalOverlay(StatisticalOverlay $overlay): self
    {
        // Only allow overlays on X/Y chart types
        if ($this->type === ChartType::Pie || $this->type === ChartType::Radar) {
            throw new InvalidArgumentException(
                "Statistical overlays are only supported for X/Y chart types (Line, Bar, Scatter). " .
                "Cannot add overlay to {$this->type->value} chart."
            );
        }

        $this->statisticalOverlays[] = $overlay;
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

        // Choose renderer based on format
        $renderer = match ($this->format) {
            ImageFormat::SVG => new SvgRenderer($this->width, $this->height),
            ImageFormat::PNG, ImageFormat::WEBP => new RasterRenderer($this->width, $this->height, $this->format),
        };

        return $renderer->render(
            $this->type,
            $this->dataSeries,
            $this->colorConfig,
            $this->gridConfig,
            $this->axisConfig,
            $this->legendConfig,
            $this->title,
            $this->xAxisLabel,
            $this->yAxisLabel,
            $this->dataLabelsEnabled,
            $this->statisticalOverlays
        );
    }
}
