<?php

declare(strict_types=1);

namespace Codryn\PHPFastChart\Renderer;

use Codryn\PHPFastChart\Chart\ChartType;
use Codryn\PHPFastChart\Configuration\AxisClipMode;
use Codryn\PHPFastChart\Configuration\AxisConfiguration;
use Codryn\PHPFastChart\Configuration\ColorConfiguration;
use Codryn\PHPFastChart\Configuration\GridConfiguration;
use Codryn\PHPFastChart\Configuration\ImageFormat;
use Codryn\PHPFastChart\Configuration\LegendConfiguration;
use Codryn\PHPFastChart\Configuration\LegendPosition;
use Codryn\PHPFastChart\Data\DataPoint;
use Codryn\PHPFastChart\Data\DataSeries;
use Codryn\PHPFastChart\Exception\InvalidArgumentException;
use Codryn\PHPFastChart\Exception\RenderException;
use Codryn\PHPFastChart\Util\ColorParser;
use Codryn\PHPFastChart\Util\MathUtil;
use GdImage;

/**
 * Raster renderer for generating PNG and WEBP charts using GD library.
 */
final class RasterRenderer implements RendererInterface
{
    private GdImage $image;

    public function __construct(
        private readonly int $width,
        private readonly int $height,
        private readonly ImageFormat $format,
    ) {
        if (!extension_loaded('gd')) {
            throw new RenderException('GD extension is required for PNG/WEBP rendering');
        }

        if ($this->width < 1 || $this->height < 1) {
            throw new InvalidArgumentException('Width and height must be at least 1 pixel');
        }

        $image = imagecreatetruecolor($this->width, $this->height);
        if ($image === false) {
            throw new RenderException('Failed to create image');
        }
        $this->image = $image;
    }

    /**
     * Render chart to PNG or WEBP binary data.
     *
     * @param ChartType $type Chart type
     * @param array<DataSeries> $dataSeries Data series to render
     * @param ColorConfiguration $colorConfig Color configuration
     * @param GridConfiguration $gridConfig Grid configuration
     * @param AxisConfiguration $axisConfig Axis configuration
     * @param LegendConfiguration $legendConfig Legend configuration
     * @param string|null $title Chart title
     * @param string|null $xAxisLabel X-axis label
     * @param string|null $yAxisLabel Y-axis label
     * @param bool $dataLabelsEnabled Whether to show data point labels
     * @return string Binary image data
     */
    public function render(
        ChartType $type,
        array $dataSeries,
        ColorConfiguration $colorConfig,
        GridConfiguration $gridConfig,
        AxisConfiguration $axisConfig,
        LegendConfiguration $legendConfig,
        ?string $title = null,
        ?string $xAxisLabel = null,
        ?string $yAxisLabel = null,
        bool $dataLabelsEnabled = false
    ): string {
        // Enable alpha blending for transparency
        imagealphablending($this->image, true);
        imagesavealpha($this->image, true);

        // Background
        $bgColor = $this->allocateColor($colorConfig->getBackgroundColor());
        imagefilledrectangle($this->image, 0, 0, $this->width - 1, $this->height - 1, $bgColor);

        // Render based on chart type
        match ($type) {
            ChartType::Line => $this->renderLineChart($dataSeries, $colorConfig, $gridConfig, $axisConfig, $dataLabelsEnabled),
            ChartType::Bar => $this->renderBarChart($dataSeries, $colorConfig, $gridConfig, $axisConfig, $dataLabelsEnabled),
            ChartType::Scatter => $this->renderScatterChart($dataSeries, $colorConfig, $gridConfig, $axisConfig, $dataLabelsEnabled),
            ChartType::Pie => $this->renderPieChart($dataSeries, $colorConfig, $dataLabelsEnabled),
            ChartType::Radar => $this->renderRadarChart($dataSeries, $colorConfig, $axisConfig, $dataLabelsEnabled),
        };

        // Render labels
        $this->renderLabels($title, $xAxisLabel, $yAxisLabel);

        // Render legend
        if ($legendConfig->isEnabled()) {
            $this->renderLegend($dataSeries, $legendConfig);
        }

        // Output image
        ob_start();
        match ($this->format) {
            ImageFormat::PNG => imagepng($this->image, null, 9),
            ImageFormat::WEBP => imagewebp($this->image, null, 90),
            default => throw new RenderException("Unsupported format for RasterRenderer: {$this->format->value}"),
        };
        $output = ob_get_clean();

        imagedestroy($this->image);

        if ($output === false) {
            throw new RenderException('Failed to generate image output');
        }

        return $output;
    }

    /**
     * Allocate a color from hex or named color string.
     */
    private function allocateColor(string $colorString): int
    {
        $rgb = ColorParser::parse($colorString);
        // Ensure RGB values are within 0-255 range
        $r = max(0, min(255, $rgb['r']));
        $g = max(0, min(255, $rgb['g']));
        $b = max(0, min(255, $rgb['b']));

        $color = imagecolorallocate($this->image, $r, $g, $b);
        if ($color === false) {
            throw new RenderException("Failed to allocate color: {$colorString}");
        }
        return $color;
    }

    /**
     * @param array<DataSeries> $dataSeries
     */
    private function renderLineChart(
        array $dataSeries,
        ColorConfiguration $colorConfig,
        GridConfiguration $gridConfig,
        AxisConfiguration $axisConfig,
        bool $dataLabelsEnabled
    ): void {
        $marginLeft = 50;
        $marginRight = 50;
        $marginTop = 50;
        $marginBottom = 50;

        $chartWidth = $this->width - $marginLeft - $marginRight;
        $chartHeight = $this->height - $marginTop - $marginBottom;

        // Collect all data points
        $allPoints = [];
        foreach ($dataSeries as $series) {
            $allPoints = array_merge($allPoints, $series->getPoints());
        }

        if (count($allPoints) === 0) {
            return;
        }

        // Validate data against axis ranges
        $this->validateDataPoints($dataSeries, $axisConfig);

        // Calculate bounds
        [$xMin, $xMax, $yMin, $yMax] = $this->calculateBounds($allPoints, $axisConfig);

        // Render grid
        if ($gridConfig->isEnabled()) {
            $this->renderGrid(
                $gridConfig,
                $marginLeft,
                $marginTop,
                $chartWidth,
                $chartHeight,
                $xMin,
                $xMax,
                $yMin,
                $yMax
            );
        }

        // Draw axes
        $axisColor = $this->allocateColor($colorConfig->getAxisColor());
        imageline($this->image, $marginLeft, $marginTop, $marginLeft, $this->height - $marginBottom, $axisColor);
        imageline($this->image, $marginLeft, $this->height - $marginBottom, $this->width - $marginRight, $this->height - $marginBottom, $axisColor);
        imagesetthickness($this->image, 2);

        // Draw each series
        foreach ($dataSeries as $series) {
            $points = $series->getPoints();
            if (count($points) === 0) {
                continue;
            }

            $seriesColor = $this->allocateColor($series->getLineColor() ?? '#3498db');
            imagesetthickness($this->image, 2);

            $prevX = null;
            $prevY = null;

            foreach ($points as $point) {
                $x = MathUtil::dataToPixel($point->x, $xMin, $xMax, $marginLeft, $marginLeft + $chartWidth);
                $y = MathUtil::dataToPixel($point->y, $yMin, $yMax, $this->height - $marginBottom, $marginTop);

                if ($prevX !== null) {
                    imageline($this->image, (int) $prevX, (int) $prevY, (int) $x, (int) $y, $seriesColor);
                }

                // Draw point marker
                imagefilledellipse($this->image, (int) $x, (int) $y, 6, 6, $seriesColor);

                // Data labels
                if ($dataLabelsEnabled && $point->label !== null) {
                    $textColor = $this->allocateColor('#333333');
                    imagestring($this->image, 2, (int) $x + 8, (int) $y - 8, $point->label, $textColor);
                }

                $prevX = $x;
                $prevY = $y;
            }
        }

        imagesetthickness($this->image, 1);
    }

    /**
     * @param array<DataSeries> $dataSeries
     */
    private function renderBarChart(
        array $dataSeries,
        ColorConfiguration $colorConfig,
        GridConfiguration $gridConfig,
        AxisConfiguration $axisConfig,
        bool $dataLabelsEnabled
    ): void {
        $marginLeft = 50;
        $marginRight = 50;
        $marginTop = 50;
        $marginBottom = 50;

        $chartWidth = $this->width - $marginLeft - $marginRight;
        $chartHeight = $this->height - $marginTop - $marginBottom;

        // Collect all data points
        $allPoints = [];
        foreach ($dataSeries as $series) {
            $allPoints = array_merge($allPoints, $series->getPoints());
        }

        if (count($allPoints) === 0) {
            return;
        }

        // Validate data
        $this->validateDataPoints($dataSeries, $axisConfig);

        // Calculate bounds
        [$xMin, $xMax, $yMin, $yMax] = $this->calculateBounds($allPoints, $axisConfig);

        // Render grid
        if ($gridConfig->isEnabled()) {
            $this->renderGrid(
                $gridConfig,
                $marginLeft,
                $marginTop,
                $chartWidth,
                $chartHeight,
                $xMin,
                $xMax,
                $yMin,
                $yMax
            );
        }

        // Draw axes
        $axisColor = $this->allocateColor($colorConfig->getAxisColor());
        imagesetthickness($this->image, 2);
        imageline($this->image, $marginLeft, $marginTop, $marginLeft, $this->height - $marginBottom, $axisColor);
        imageline($this->image, $marginLeft, $this->height - $marginBottom, $this->width - $marginRight, $this->height - $marginBottom, $axisColor);

        // Calculate bar properties
        $numSeries = count($dataSeries);
        $firstSeries = reset($dataSeries);
        $numBars = $firstSeries !== false ? count($firstSeries->getPoints()) : 0;

        if ($numBars === 0) {
            return;
        }

        $totalBarWidth = $chartWidth / $numBars;
        $barWidth = $totalBarWidth / ($numSeries + 1);

        // Draw bars for each series
        $seriesIndex = 0;
        foreach ($dataSeries as $series) {
            $seriesColor = $this->allocateColor($series->getLineColor() ?? '#3498db');
            $points = $series->getPoints();

            $barIndex = 0;
            foreach ($points as $point) {
                $x = $marginLeft + ($barIndex * $totalBarWidth) + ($seriesIndex * $barWidth) + $barWidth / 2;
                $y = MathUtil::dataToPixel($point->y, $yMin, $yMax, $this->height - $marginBottom, $marginTop);
                $barHeight = ($this->height - $marginBottom) - $y;

                imagefilledrectangle(
                    $this->image,
                    (int) $x,
                    (int) $y,
                    (int) ($x + $barWidth),
                    $this->height - $marginBottom,
                    $seriesColor
                );

                // Data labels
                if ($dataLabelsEnabled && $point->label !== null) {
                    $textColor = $this->allocateColor('#333333');
                    imagestring($this->image, 2, (int) ($x + $barWidth / 4), (int) $y - 15, $point->label, $textColor);
                }

                $barIndex++;
            }
            $seriesIndex++;
        }

        imagesetthickness($this->image, 1);
    }

    /**
     * @param array<DataSeries> $dataSeries
     */
    private function renderScatterChart(
        array $dataSeries,
        ColorConfiguration $colorConfig,
        GridConfiguration $gridConfig,
        AxisConfiguration $axisConfig,
        bool $dataLabelsEnabled
    ): void {
        $marginLeft = 50;
        $marginRight = 50;
        $marginTop = 50;
        $marginBottom = 50;

        $chartWidth = $this->width - $marginLeft - $marginRight;
        $chartHeight = $this->height - $marginTop - $marginBottom;

        // Collect all data points
        $allPoints = [];
        foreach ($dataSeries as $series) {
            $allPoints = array_merge($allPoints, $series->getPoints());
        }

        if (count($allPoints) === 0) {
            return;
        }

        // Validate data
        $this->validateDataPoints($dataSeries, $axisConfig);

        // Calculate bounds
        [$xMin, $xMax, $yMin, $yMax] = $this->calculateBounds($allPoints, $axisConfig);

        // Render grid
        if ($gridConfig->isEnabled()) {
            $this->renderGrid(
                $gridConfig,
                $marginLeft,
                $marginTop,
                $chartWidth,
                $chartHeight,
                $xMin,
                $xMax,
                $yMin,
                $yMax
            );
        }

        // Draw axes
        $axisColor = $this->allocateColor($colorConfig->getAxisColor());
        imagesetthickness($this->image, 2);
        imageline($this->image, $marginLeft, $marginTop, $marginLeft, $this->height - $marginBottom, $axisColor);
        imageline($this->image, $marginLeft, $this->height - $marginBottom, $this->width - $marginRight, $this->height - $marginBottom, $axisColor);

        // Draw scatter points
        foreach ($dataSeries as $series) {
            $seriesColor = $this->allocateColor($series->getLineColor() ?? '#3498db');

            foreach ($series->getPoints() as $point) {
                $x = MathUtil::dataToPixel($point->x, $xMin, $xMax, $marginLeft, $marginLeft + $chartWidth);
                $y = MathUtil::dataToPixel($point->y, $yMin, $yMax, $this->height - $marginBottom, $marginTop);

                imagefilledellipse($this->image, (int) $x, (int) $y, 8, 8, $seriesColor);

                // Data labels
                if ($dataLabelsEnabled && $point->label !== null) {
                    $textColor = $this->allocateColor('#333333');
                    imagestring($this->image, 2, (int) $x + 8, (int) $y - 8, $point->label, $textColor);
                }
            }
        }

        imagesetthickness($this->image, 1);
    }

    /**
     * @param array<DataSeries> $dataSeries
     */
    private function renderPieChart(
        array $dataSeries,
        ColorConfiguration $colorConfig,
        bool $dataLabelsEnabled
    ): void {
        if (count($dataSeries) === 0) {
            return;
        }

        // Pie chart uses first series only
        $series = $dataSeries[0];
        $points = $series->getPoints();

        if (count($points) === 0) {
            return;
        }

        // Calculate center and radius
        $centerX = (int) ($this->width / 2);
        $centerY = (int) ($this->height / 2);
        $radius = (int) (min($this->width, $this->height) * 0.35);

        // Calculate total value
        $total = 0.0;
        foreach ($points as $point) {
            $total += $point->y;
        }

        if ($total === 0.0) {
            return;
        }

        // Generate color palette for slices
        $colors = [
            '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF',
            '#FF9F40', '#FF6384', '#C9CBCF', '#4BC0C0', '#FF6384',
        ];

        // Draw slices
        $currentAngle = 270.0; // Start at top (12 o'clock) - GD uses 0 degrees at 3 o'clock

        foreach ($points as $index => $point) {
            $sliceAngle = ($point->y / $total) * 360.0;
            $endAngle = $currentAngle + $sliceAngle;

            // Use series color if set, otherwise use palette
            $colorStr = $series->getLineColor() ?? $colors[$index % count($colors)];
            $fillColor = $this->allocateColor($colorStr);

            // Draw filled arc (pie slice)
            if ($sliceAngle >= 359.99) {
                // Full circle
                imagefilledellipse($this->image, $centerX, $centerY, $radius * 2, $radius * 2, $fillColor);
            } else {
                imagefilledarc(
                    $this->image,
                    $centerX,
                    $centerY,
                    $radius * 2,
                    $radius * 2,
                    (int) $currentAngle,
                    (int) $endAngle,
                    $fillColor,
                    IMG_ARC_PIE
                );
            }

            // Draw slice border
            $white = $this->allocateColor('#FFFFFF');
            imagearc(
                $this->image,
                $centerX,
                $centerY,
                $radius * 2,
                $radius * 2,
                (int) $currentAngle,
                (int) $endAngle,
                $white
            );

            // Add data label if enabled
            if ($dataLabelsEnabled) {
                $labelAngle = $currentAngle + ($sliceAngle / 2);
                $labelRadius = $radius * 0.65;
                $labelCoords = MathUtil::polarToCartesian($labelAngle, $labelRadius, (float) $centerX, (float) $centerY);

                $percentage = ($point->y / $total) * 100;
                $labelText = $point->label ?? sprintf('%.1f%%', $percentage);

                $textColor = $this->allocateColor('#FFFFFF');
                $textX = (int) ($labelCoords['x'] - (strlen($labelText) * 3));
                $textY = (int) ($labelCoords['y'] - 4);
                imagestring($this->image, 2, $textX, $textY, $labelText, $textColor);
            }

            $currentAngle = $endAngle;
        }
    }

    /**
     * @param array<DataSeries> $dataSeries
     */
    private function renderRadarChart(
        array $dataSeries,
        ColorConfiguration $colorConfig,
        AxisConfiguration $axisConfig,
        bool $dataLabelsEnabled
    ): void {
        if (count($dataSeries) === 0) {
            return;
        }

        // Calculate center and radius
        $marginTop = 80;
        $marginBottom = 80;
        $marginSide = 80;

        $centerX = (int) ($this->width / 2);
        $centerY = (int) ($this->height / 2);
        $maxRadius = (int) min(
            ($this->width - $marginSide * 2) / 2,
            ($this->height - $marginTop - $marginBottom) / 2
        );

        // Get number of axes from first series
        $firstSeries = $dataSeries[0];
        $axisCount = count($firstSeries->getPoints());

        if ($axisCount < 3) {
            return;
        }

        // Calculate data range
        $allPoints = [];
        foreach ($dataSeries as $series) {
            $allPoints = array_merge($allPoints, $series->getPoints());
        }

        $minValue = $allPoints[0]->y;
        $maxValue = $allPoints[0]->y;
        foreach ($allPoints as $point) {
            $minValue = min($minValue, $point->y);
            $maxValue = max($maxValue, $point->y);
        }

        // Apply axis configuration if set
        if ($axisConfig->hasYRange()) {
            $minValue = $axisConfig->getYMin() ?? $minValue;
            $maxValue = $axisConfig->getYMax() ?? $maxValue;
        }

        $valueRange = $maxValue - $minValue;
        if ($valueRange === 0.0) {
            $valueRange = 1.0;
        }

        // Draw background grid circles
        $axisColorStr = $colorConfig->getAxisColor();
        $axisColor = $this->allocateColor($axisColorStr);
        $gridLevels = 5;

        for ($i = 1; $i <= $gridLevels; $i++) {
            $gridRadius = (int) (($maxRadius / $gridLevels) * $i);
            imageellipse($this->image, $centerX, $centerY, $gridRadius * 2, $gridRadius * 2, $axisColor);
        }

        // Draw axis lines and labels
        $angleStep = 360.0 / $axisCount;
        $startAngle = 270.0; // Start at top (GD uses 0 degrees at 3 o'clock)

        for ($i = 0; $i < $axisCount; $i++) {
            $angle = $startAngle + ($i * $angleStep);
            $coords = MathUtil::polarToCartesian($angle, (float) $maxRadius, (float) $centerX, (float) $centerY);

            // Draw axis line
            imageline(
                $this->image,
                $centerX,
                $centerY,
                (int) $coords['x'],
                (int) $coords['y'],
                $axisColor
            );

            // Draw axis label
            $labelCoords = MathUtil::polarToCartesian($angle, $maxRadius + 20.0, (float) $centerX, (float) $centerY);
            $label = $firstSeries->getPoints()[$i]->label ?? "Axis $i";

            $textColor = $this->allocateColor('#333333');
            $textX = (int) ($labelCoords['x'] - (strlen($label) * 3));
            $textY = (int) ($labelCoords['y'] - 4);
            imagestring($this->image, 2, $textX, $textY, $label, $textColor);
        }

        // Draw data series
        foreach ($dataSeries as $seriesIndex => $series) {
            $points = $series->getPoints();

            if (count($points) !== $axisCount) {
                continue; // Skip series with mismatched point count
            }

            // Build polygon points array
            $polygonPoints = [];

            for ($i = 0; $i < $axisCount; $i++) {
                $angle = $startAngle + ($i * $angleStep);
                $normalizedValue = ($points[$i]->y - $minValue) / $valueRange;
                $pointRadius = $normalizedValue * $maxRadius;

                $coords = MathUtil::polarToCartesian($angle, $pointRadius, (float) $centerX, (float) $centerY);
                $polygonPoints[] = (int) $coords['x'];
                $polygonPoints[] = (int) $coords['y'];
            }

            $colorsArray = [
                '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF',
                '#FF9F40', '#FF6384', '#C9CBCF', '#4BC0C0', '#FF6384',
            ];
            $colorStr = $series->getLineColor() ?? $colorsArray[$seriesIndex % count($colorsArray)];
            $lineColor = $this->allocateColor($colorStr);

            // Parse color and create semi-transparent version for fill
            $rgb = ColorParser::parse($colorStr);
            $r = max(0, min(255, $rgb['r']));
            $g = max(0, min(255, $rgb['g']));
            $b = max(0, min(255, $rgb['b']));
            $fillColor = imagecolorallocatealpha($this->image, $r, $g, $b, 90);

            // Draw filled polygon
            if ($fillColor !== false) {
                imagefilledpolygon($this->image, $polygonPoints, $fillColor);
            }

            // Draw polygon outline
            imagepolygon($this->image, $polygonPoints, $lineColor);

            // Draw points
            for ($i = 0; $i < $axisCount; $i++) {
                $angle = $startAngle + ($i * $angleStep);
                $normalizedValue = ($points[$i]->y - $minValue) / $valueRange;
                $pointRadius = $normalizedValue * $maxRadius;

                $coords = MathUtil::polarToCartesian($angle, $pointRadius, (float) $centerX, (float) $centerY);

                $white = $this->allocateColor('#FFFFFF');
                imagefilledellipse(
                    $this->image,
                    (int) $coords['x'],
                    (int) $coords['y'],
                    8,
                    8,
                    $lineColor
                );
                imageellipse(
                    $this->image,
                    (int) $coords['x'],
                    (int) $coords['y'],
                    8,
                    8,
                    $white
                );
            }
        }
    }

    private function renderLabels(?string $title, ?string $xAxisLabel, ?string $yAxisLabel): void
    {
        $labelColor = $this->allocateColor('#333333');

        if ($title !== null) {
            imagestring($this->image, 5, (int) ($this->width / 2 - (strlen($title) * 4)), 25, $title, $labelColor);
        }

        if ($xAxisLabel !== null) {
            imagestring($this->image, 3, (int) ($this->width / 2 - (strlen($xAxisLabel) * 3)), $this->height - 20, $xAxisLabel, $labelColor);
        }

        if ($yAxisLabel !== null) {
            // Y-axis label (rotated text is complex in GD, so placing horizontally)
            imagestring($this->image, 3, 5, (int) ($this->height / 2), $yAxisLabel, $labelColor);
        }
    }

    /**
     * @param array<DataSeries> $dataSeries
     */
    private function renderLegend(array $dataSeries, LegendConfiguration $legendConfig): void
    {
        $itemHeight = 20;
        $itemWidth = 120;
        $padding = 10;
        $symbolSize = 12;

        $legendHeight = count($dataSeries) * $itemHeight + $padding * 2;
        $legendWidth = $itemWidth + $padding * 2;

        // Determine position
        [$x, $y] = match ($legendConfig->getPosition()) {
            LegendPosition::Top => [($this->width - $legendWidth) / 2, 10],
            LegendPosition::Right => [$this->width - $legendWidth - 10, ($this->height - $legendHeight) / 2],
            LegendPosition::Bottom => [($this->width - $legendWidth) / 2, $this->height - $legendHeight - 10],
            LegendPosition::Left => [10, ($this->height - $legendHeight) / 2],
        };

        // Draw legend background
        $bgColor = $this->allocateColor($legendConfig->getBackgroundColor());
        $borderColor = $this->allocateColor($legendConfig->getBorderColor());
        imagefilledrectangle($this->image, (int) $x, (int) $y, (int) ($x + $legendWidth), (int) ($y + $legendHeight), $bgColor);
        imagerectangle($this->image, (int) $x, (int) $y, (int) ($x + $legendWidth), (int) ($y + $legendHeight), $borderColor);

        // Draw legend items
        $textColor = $this->allocateColor($legendConfig->getTextColor());
        $itemY = (int) $y + $padding;

        foreach ($dataSeries as $series) {
            // Draw color symbol
            $seriesColor = $this->allocateColor($series->getLineColor() ?? '#3498db');
            imagefilledrectangle(
                $this->image,
                (int) $x + $padding,
                $itemY + ($itemHeight - $symbolSize) / 2,
                (int) $x + $padding + $symbolSize,
                $itemY + ($itemHeight - $symbolSize) / 2 + $symbolSize,
                $seriesColor
            );

            // Draw series name
            imagestring($this->image, 3, (int) $x + $padding + $symbolSize + 8, $itemY + 4, $series->getName(), $textColor);

            $itemY += $itemHeight;
        }
    }

    private function renderGrid(
        GridConfiguration $gridConfig,
        int $marginLeft,
        int $marginTop,
        int $chartWidth,
        int $chartHeight,
        float $xMin,
        float $xMax,
        float $yMin,
        float $yMax
    ): void {
        $gridColor = $this->allocateColor($gridConfig->getColor());
        imagesetthickness($this->image, (int) $gridConfig->getLineWidth());

        // Horizontal grid lines
        if ($gridConfig->showHorizontalLines()) {
            $spacing = $gridConfig->getSpacing() ?? MathUtil::calculateGridSpacing($yMin, $yMax, 10);
            $yValue = ceil($yMin / $spacing) * $spacing;

            while ($yValue <= $yMax) {
                $y = MathUtil::dataToPixel($yValue, $yMin, $yMax, $this->height - $marginTop - $chartHeight, $marginTop);
                imageline($this->image, $marginLeft, (int) $y, $marginLeft + $chartWidth, (int) $y, $gridColor);
                $yValue += $spacing;
            }
        }

        // Vertical grid lines
        if ($gridConfig->showVerticalLines()) {
            $spacing = $gridConfig->getSpacing() ?? MathUtil::calculateGridSpacing($xMin, $xMax, 10);
            $xValue = ceil($xMin / $spacing) * $spacing;

            while ($xValue <= $xMax) {
                $x = MathUtil::dataToPixel($xValue, $xMin, $xMax, $marginLeft, $marginLeft + $chartWidth);
                imageline($this->image, (int) $x, $marginTop, (int) $x, $marginTop + $chartHeight, $gridColor);
                $xValue += $spacing;
            }
        }

        imagesetthickness($this->image, 1);
    }

    /**
     * @param array<DataPoint> $points
     * @return array{float, float, float, float} [xMin, xMax, yMin, yMax]
     */
    private function calculateBounds(array $points, AxisConfiguration $axisConfig): array
    {
        if (count($points) === 0) {
            return [0.0, 1.0, 0.0, 1.0];
        }

        $xValues = array_map(fn (DataPoint $p) => $p->x, $points);
        $yValues = array_map(fn (DataPoint $p) => $p->y, $points);

        $xMin = $axisConfig->getXMin() ?? min($xValues);
        $xMax = $axisConfig->getXMax() ?? max($xValues);
        $yMin = $axisConfig->getYMin() ?? (min($yValues) < 0 ? min($yValues) : 0.0);
        $yMax = $axisConfig->getYMax() ?? max($yValues);

        // Auto-scale with nice numbers if needed
        if ($axisConfig->getYMin() === null || $axisConfig->getYMax() === null) {
            $range = $yMax - $yMin;
            $niceRange = MathUtil::calculateNiceNumber($range);
            $niceTick = MathUtil::calculateNiceNumber($niceRange / 10);

            if ($axisConfig->getYMin() === null) {
                $yMin = floor($yMin / $niceTick) * $niceTick;
            }
            if ($axisConfig->getYMax() === null) {
                $yMax = ceil($yMax / $niceTick) * $niceTick;
            }
        }

        return [$xMin, $xMax, $yMin, $yMax];
    }

    /**
     * @param array<DataSeries> $dataSeries
     */
    private function validateDataPoints(array $dataSeries, AxisConfiguration $axisConfig): void
    {
        if ($axisConfig->getClipMode() !== AxisClipMode::Throw) {
            return;
        }

        $minX = $axisConfig->getXMin();
        $maxX = $axisConfig->getXMax();
        $minY = $axisConfig->getYMin();
        $maxY = $axisConfig->getYMax();

        foreach ($dataSeries as $series) {
            foreach ($series->getPoints() as $point) {
                $x = $point->x;
                $y = $point->y;

                if ($minX !== null && $x < $minX) {
                    throw new InvalidArgumentException("Data point X value {$x} is below minimum {$minX}");
                }
                if ($maxX !== null && $x > $maxX) {
                    throw new InvalidArgumentException("Data point X value {$x} is above maximum {$maxX}");
                }
                if ($minY !== null && $y < $minY) {
                    throw new InvalidArgumentException("Data point Y value {$y} is below minimum {$minY}");
                }
                if ($maxY !== null && $y > $maxY) {
                    throw new InvalidArgumentException("Data point Y value {$y} is above maximum {$maxY}");
                }
            }
        }
    }
}
