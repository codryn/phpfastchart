<?php

declare(strict_types=1);

namespace Codryn\PHPFastChart\Renderer;

use Codryn\PHPFastChart\Chart\ChartType;
use Codryn\PHPFastChart\Configuration\AxisClipMode;
use Codryn\PHPFastChart\Configuration\AxisConfiguration;
use Codryn\PHPFastChart\Configuration\ColorConfiguration;
use Codryn\PHPFastChart\Configuration\GridConfiguration;
use Codryn\PHPFastChart\Configuration\LegendConfiguration;
use Codryn\PHPFastChart\Configuration\LegendPosition;
use Codryn\PHPFastChart\Data\DataSeries;
use Codryn\PHPFastChart\Util\ColorParser;
use Codryn\PHPFastChart\Util\MathUtil;

/**
 * SVG renderer for generating charts as SVG XML.
 */
final class SvgRenderer implements RendererInterface
{
    public function __construct(
        private readonly int $width,
        private readonly int $height,
    ) {
    }

    /**
     * Render chart to SVG string.
     *
     * @param ChartType $type Chart type
     * @param array<DataSeries> $dataSeries Data series to render
     * @param ColorConfiguration $colorConfig Color configuration
     * @param GridConfiguration $gridConfig Grid configuration
     * @param AxisConfiguration $axisConfig Axis configuration
     * @param string|null $title Chart title
     * @param string|null $xAxisLabel X-axis label
     * @param string|null $yAxisLabel Y-axis label
     * @param bool $dataLabelsEnabled Whether to show data point labels
     * @return string SVG XML content
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
        $svg = sprintf(
            '<svg xmlns="http://www.w3.org/2000/svg" width="%d" height="%d" viewBox="0 0 %d %d">',
            $this->width,
            $this->height,
            $this->width,
            $this->height
        );

        // Background
        $bgColor = ColorParser::parse($colorConfig->getBackgroundColor());
        $svg .= sprintf(
            '<rect width="100%%" height="100%%" fill="rgb(%d,%d,%d)" />',
            $bgColor['r'],
            $bgColor['g'],
            $bgColor['b']
        );

        // Render based on chart type
        match ($type) {
            ChartType::Line => $svg .= $this->renderLineChart($dataSeries, $colorConfig, $gridConfig, $axisConfig, $dataLabelsEnabled),
            ChartType::Bar => $svg .= $this->renderBarChart($dataSeries, $colorConfig, $gridConfig, $axisConfig, $dataLabelsEnabled),
            ChartType::Scatter => $svg .= $this->renderScatterChart($dataSeries, $colorConfig, $gridConfig, $axisConfig, $dataLabelsEnabled),
            default => $svg .= $this->renderPlaceholder($type),
        };

        // Render labels
        $svg .= $this->renderLabels($title, $xAxisLabel, $yAxisLabel);

        // Render legend
        if ($legendConfig->isEnabled()) {
            $svg .= $this->renderLegend($dataSeries, $legendConfig);
        }

        $svg .= '</svg>';

        return $svg;
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
    ): string {
        $content = '';

        // Simple rendering with margins
        $marginLeft = 50;
        $marginRight = 50;
        $marginTop = 50;
        $marginBottom = 50;

        $chartWidth = $this->width - $marginLeft - $marginRight;
        $chartHeight = $this->height - $marginTop - $marginBottom;

        // Draw axes
        $axisColor = ColorParser::parse($colorConfig->getAxisColor());
        $content .= sprintf(
            '<line x1="%d" y1="%d" x2="%d" y2="%d" stroke="rgb(%d,%d,%d)" stroke-width="2" />',
            $marginLeft,
            $marginTop,
            $marginLeft,
            $this->height - $marginBottom,
            $axisColor['r'],
            $axisColor['g'],
            $axisColor['b']
        );
        $content .= sprintf(
            '<line x1="%d" y1="%d" x2="%d" y2="%d" stroke="rgb(%d,%d,%d)" stroke-width="2" />',
            $marginLeft,
            $this->height - $marginBottom,
            $this->width - $marginRight,
            $this->height - $marginBottom,
            $axisColor['r'],
            $axisColor['g'],
            $axisColor['b']
        );

        // Collect all data points for bounds calculation
        $allPoints = [];
        foreach ($dataSeries as $series) {
            $allPoints = array_merge($allPoints, $series->getPoints());
        }

        if (count($allPoints) === 0) {
            return $content;
        }

        // Validate data against axis ranges if clip mode is Throw
        $this->validateDataPoints($dataSeries, $axisConfig);

        // Calculate bounds with axis ranges or auto-scale
        if ($axisConfig->hasXRange()) {
            $minX = $axisConfig->getXMin() ?? 0.0;
            $maxX = $axisConfig->getXMax() ?? 1.0;
        } else {
            $minX = $allPoints[0]->x;
            $maxX = $allPoints[0]->x;
            foreach ($allPoints as $point) {
                $minX = min($minX, $point->x);
                $maxX = max($maxX, $point->x);
            }
        }

        if ($axisConfig->hasYRange()) {
            $minY = $axisConfig->getYMin() ?? 0.0;
            $maxY = $axisConfig->getYMax() ?? 1.0;
        } else {
            $minY = $allPoints[0]->y;
            $maxY = $allPoints[0]->y;
            foreach ($allPoints as $point) {
                $minY = min($minY, $point->y);
                $maxY = max($maxY, $point->y);
            }
        }

        $rangeX = $maxX - $minX;
        $rangeY = $maxY - $minY;
        if ($rangeX === 0.0) {
            $rangeX = 1.0;
        }
        if ($rangeY === 0.0) {
            $rangeY = 1.0;
        }

        // Render grid if enabled
        if ($gridConfig->isEnabled()) {
            $content .= $this->renderGrid(
                $gridConfig,
                $marginLeft,
                $marginTop,
                $chartWidth,
                $chartHeight,
                $minX,
                $maxX,
                $minY,
                $maxY,
                $rangeX,
                $rangeY
            );
        }

        // Render each data series
        foreach ($dataSeries as $series) {
            $points = $series->getPoints();
            if (count($points) === 0) {
                continue;
            }

            // Build path using global bounds
            $pathData = '';
            foreach ($points as $i => $point) {
                $x = $marginLeft + (($point->x - $minX) / $rangeX) * $chartWidth;
                $y = $marginTop + $chartHeight - (($point->y - $minY) / $rangeY) * $chartHeight;

                $pathData .= ($i === 0 ? 'M' : 'L') . " {$x},{$y} ";
            }

            // Use series color or default
            $color = $series->getLineColor() ?? '#3498db';
            $lineColor = ColorParser::parse($color);

            $content .= sprintf(
                '<path d="%s" fill="none" stroke="rgb(%d,%d,%d)" stroke-width="2" />',
                trim($pathData),
                $lineColor['r'],
                $lineColor['g'],
                $lineColor['b']
            );

            // Render data labels if enabled
            if ($dataLabelsEnabled) {
                foreach ($points as $point) {
                    $x = $marginLeft + (($point->x - $minX) / $rangeX) * $chartWidth;
                    $y = $marginTop + $chartHeight - (($point->y - $minY) / $rangeY) * $chartHeight;

                    $content .= sprintf(
                        '<text x="%.2f" y="%.2f" font-size="12" fill="#333" text-anchor="middle" dy="-8">%g</text>',
                        $x,
                        $y,
                        $point->y
                    );
                }
            }
        }

        return $content;
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
    ): string {
        $content = '';

        $marginLeft = 50;
        $marginRight = 50;
        $marginTop = 50;
        $marginBottom = 50;

        $chartWidth = $this->width - $marginLeft - $marginRight;
        $chartHeight = $this->height - $marginTop - $marginBottom;

        // Draw axes
        $axisColor = ColorParser::parse($colorConfig->getAxisColor());
        $content .= sprintf(
            '<line x1="%d" y1="%d" x2="%d" y2="%d" stroke="rgb(%d,%d,%d)" stroke-width="2" />',
            $marginLeft,
            $marginTop,
            $marginLeft,
            $this->height - $marginBottom,
            $axisColor['r'],
            $axisColor['g'],
            $axisColor['b']
        );
        $content .= sprintf(
            '<line x1="%d" y1="%d" x2="%d" y2="%d" stroke="rgb(%d,%d,%d)" stroke-width="2" />',
            $marginLeft,
            $this->height - $marginBottom,
            $this->width - $marginRight,
            $this->height - $marginBottom,
            $axisColor['r'],
            $axisColor['g'],
            $axisColor['b']
        );

        // Collect all points for bounds
        $allPoints = [];
        foreach ($dataSeries as $series) {
            $allPoints = array_merge($allPoints, $series->getPoints());
        }

        if (count($allPoints) === 0) {
            return $content;
        }

        // Validate data against axis ranges if clip mode is Throw
        $this->validateDataPoints($dataSeries, $axisConfig);

        // Calculate bounds with axis ranges or auto-scale
        if ($axisConfig->hasXRange()) {
            $minX = $axisConfig->getXMin() ?? 0.0;
            $maxX = $axisConfig->getXMax() ?? 1.0;
        } else {
            $minX = $allPoints[0]->x;
            $maxX = $allPoints[0]->x;
            foreach ($allPoints as $point) {
                $minX = min($minX, $point->x);
                $maxX = max($maxX, $point->x);
            }
        }

        if ($axisConfig->hasYRange()) {
            $minY = $axisConfig->getYMin() ?? 0.0;
            $maxY = $axisConfig->getYMax() ?? 1.0;
        } else {
            $minY = $allPoints[0]->y;
            $maxY = $allPoints[0]->y;
            foreach ($allPoints as $point) {
                $minY = min($minY, $point->y);
                $maxY = max($maxY, $point->y);
            }
        }

        $rangeY = $maxY - $minY;
        $rangeX = $maxX - $minX;
        if ($rangeY === 0.0) {
            $rangeY = 1.0;
        }
        if ($rangeX === 0.0) {
            $rangeX = 1.0;
        }

        // Render grid if enabled
        if ($gridConfig->isEnabled()) {
            $content .= $this->renderGrid(
                $gridConfig,
                $marginLeft,
                $marginTop,
                $chartWidth,
                $chartHeight,
                $minX,
                $maxX,
                $minY,
                $maxY,
                $rangeX,
                $rangeY
            );
        }

        // Render bars for each series using global bounds
        foreach ($dataSeries as $series) {
            $points = $series->getPoints();
            if (count($points) === 0) {
                continue;
            }

            // Calculate bar width
            $barWidth = $chartWidth / (count($points) * 2);

            // Render bars
            $color = $series->getLineColor() ?? '#3498db';
            $barColor = ColorParser::parse($color);

            foreach ($points as $i => $point) {
                $barHeight = (($point->y - $minY) / $rangeY) * $chartHeight;
                $x = $marginLeft + ($i * 2 + 0.5) * $barWidth;
                $y = $this->height - $marginBottom - $barHeight;

                $content .= sprintf(
                    '<rect x="%f" y="%f" width="%f" height="%f" fill="rgb(%d,%d,%d)" />',
                    $x,
                    $y,
                    $barWidth,
                    $barHeight,
                    $barColor['r'],
                    $barColor['g'],
                    $barColor['b']
                );

                // Render data label if enabled
                if ($dataLabelsEnabled) {
                    $labelX = $x + $barWidth / 2;
                    $labelY = $y - 5;

                    $content .= sprintf(
                        '<text x="%.2f" y="%.2f" font-size="12" fill="#333" text-anchor="middle">%g</text>',
                        $labelX,
                        $labelY,
                        $point->y
                    );
                }
            }
        }

        return $content;
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
    ): string {
        $content = '';

        $marginLeft = 50;
        $marginRight = 50;
        $marginTop = 50;
        $marginBottom = 50;

        $chartWidth = $this->width - $marginLeft - $marginRight;
        $chartHeight = $this->height - $marginTop - $marginBottom;

        // Draw axes
        $axisColor = ColorParser::parse($colorConfig->getAxisColor());
        $content .= sprintf(
            '<line x1="%d" y1="%d" x2="%d" y2="%d" stroke="rgb(%d,%d,%d)" stroke-width="2" />',
            $marginLeft,
            $marginTop,
            $marginLeft,
            $this->height - $marginBottom,
            $axisColor['r'],
            $axisColor['g'],
            $axisColor['b']
        );
        $content .= sprintf(
            '<line x1="%d" y1="%d" x2="%d" y2="%d" stroke="rgb(%d,%d,%d)" stroke-width="2" />',
            $marginLeft,
            $this->height - $marginBottom,
            $this->width - $marginRight,
            $this->height - $marginBottom,
            $axisColor['r'],
            $axisColor['g'],
            $axisColor['b']
        );

        // Collect all data points for bounds calculation
        $allPoints = [];
        foreach ($dataSeries as $series) {
            $allPoints = array_merge($allPoints, $series->getPoints());
        }

        if (count($allPoints) === 0) {
            return $content;
        }

        // Validate data against axis ranges if clip mode is Throw
        $this->validateDataPoints($dataSeries, $axisConfig);

        // Calculate bounds with axis ranges or auto-scale
        if ($axisConfig->hasXRange()) {
            $minX = $axisConfig->getXMin() ?? 0.0;
            $maxX = $axisConfig->getXMax() ?? 1.0;
        } else {
            $minX = $allPoints[0]->x;
            $maxX = $allPoints[0]->x;
            foreach ($allPoints as $point) {
                $minX = min($minX, $point->x);
                $maxX = max($maxX, $point->x);
            }
        }

        if ($axisConfig->hasYRange()) {
            $minY = $axisConfig->getYMin() ?? 0.0;
            $maxY = $axisConfig->getYMax() ?? 1.0;
        } else {
            $minY = $allPoints[0]->y;
            $maxY = $allPoints[0]->y;
            foreach ($allPoints as $point) {
                $minY = min($minY, $point->y);
                $maxY = max($maxY, $point->y);
            }
        }

        $rangeX = $maxX - $minX;
        $rangeY = $maxY - $minY;
        if ($rangeX === 0.0) {
            $rangeX = 1.0;
        }
        if ($rangeY === 0.0) {
            $rangeY = 1.0;
        }

        // Render grid if enabled
        if ($gridConfig->isEnabled()) {
            $content .= $this->renderGrid(
                $gridConfig,
                $marginLeft,
                $marginTop,
                $chartWidth,
                $chartHeight,
                $minX,
                $maxX,
                $minY,
                $maxY,
                $rangeX,
                $rangeY
            );
        }

        // Render each data series as scatter points
        foreach ($dataSeries as $series) {
            $points = $series->getPoints();
            if (count($points) === 0) {
                continue;
            }

            // Get series color
            $color = $series->getLineColor() ?? '#3498db';
            $pointColor = ColorParser::parse($color);

            // Render each point as a circle
            foreach ($points as $point) {
                // Skip points outside axis ranges if clipping is enabled
                if ($axisConfig->getClipMode() === AxisClipMode::Clip) {
                    if ($axisConfig->hasXRange() && ($point->x < $minX || $point->x > $maxX)) {
                        continue;
                    }
                    if ($axisConfig->hasYRange() && ($point->y < $minY || $point->y > $maxY)) {
                        continue;
                    }
                }

                $x = $marginLeft + (($point->x - $minX) / $rangeX) * $chartWidth;
                $y = $this->height - $marginBottom - (($point->y - $minY) / $rangeY) * $chartHeight;

                $content .= sprintf(
                    '<circle cx="%.2f" cy="%.2f" r="4" fill="rgb(%d,%d,%d)" />',
                    $x,
                    $y,
                    $pointColor['r'],
                    $pointColor['g'],
                    $pointColor['b']
                );

                // Render data label if enabled
                if ($dataLabelsEnabled) {
                    $labelX = $x;
                    $labelY = $y - 8;

                    $content .= sprintf(
                        '<text x="%.2f" y="%.2f" font-size="12" fill="#333" text-anchor="middle">%g</text>',
                        $labelX,
                        $labelY,
                        $point->y
                    );
                }
            }
        }

        return $content;
    }

    /**
     * Render grid lines.
     */
    private function renderGrid(
        GridConfiguration $gridConfig,
        int $marginLeft,
        int $marginTop,
        int $chartWidth,
        int $chartHeight,
        float $minX,
        float $maxX,
        float $minY,
        float $maxY,
        float $rangeX,
        float $rangeY
    ): string {
        $content = '';

        $gridColor = ColorParser::parse($gridConfig->getColor());
        $strokeWidth = $gridConfig->getLineWidth();

        // Calculate grid spacing
        $spacing = $gridConfig->getSpacing() ?? MathUtil::calculateGridSpacing($minY, $maxY, $chartHeight);

        // Horizontal grid lines (along Y-axis)
        if ($gridConfig->showHorizontalLines()) {
            $yValue = ceil($minY / $spacing) * $spacing;

            while ($yValue <= $maxY) {
                if ($yValue > $minY && $yValue < $maxY) {
                    $yPixel = $marginTop + $chartHeight - (($yValue - $minY) / $rangeY) * $chartHeight;

                    $content .= sprintf(
                        '<line x1="%d" y1="%.2f" x2="%d" y2="%.2f" stroke="rgb(%d,%d,%d)" stroke-width="%.1f" opacity="0.7" />',
                        $marginLeft,
                        $yPixel,
                        $marginLeft + $chartWidth,
                        $yPixel,
                        $gridColor['r'],
                        $gridColor['g'],
                        $gridColor['b'],
                        $strokeWidth
                    );
                }

                $yValue += $spacing;
            }
        }

        // Vertical grid lines (along X-axis)
        if ($gridConfig->showVerticalLines()) {
            $xSpacing = $gridConfig->getSpacing() ?? MathUtil::calculateGridSpacing($minX, $maxX, $chartWidth);
            $xValue = ceil($minX / $xSpacing) * $xSpacing;

            while ($xValue <= $maxX) {
                if ($xValue > $minX && $xValue < $maxX) {
                    $xPixel = $marginLeft + (($xValue - $minX) / $rangeX) * $chartWidth;

                    $content .= sprintf(
                        '<line x1="%.2f" y1="%d" x2="%.2f" y2="%d" stroke="rgb(%d,%d,%d)" stroke-width="%.1f" opacity="0.7" />',
                        $xPixel,
                        $marginTop,
                        $xPixel,
                        $marginTop + $chartHeight,
                        $gridColor['r'],
                        $gridColor['g'],
                        $gridColor['b'],
                        $strokeWidth
                    );
                }

                $xValue += $xSpacing;
            }
        }

        return $content;
    }

    private function renderPlaceholder(ChartType $type): string
    {
        return sprintf(
            '<text x="50%%" y="50%%" text-anchor="middle" fill="#333">%s chart (not yet implemented)</text>',
            $type->value
        );
    }

    /**
     * Validate data points against axis ranges.
     *
     * @param array<DataSeries> $dataSeries
     * @throws \InvalidArgumentException If data is out of range and clip mode is Throw
     */
    private function validateDataPoints(array $dataSeries, AxisConfiguration $axisConfig): void
    {
        if ($axisConfig->getClipMode() === AxisClipMode::Clip) {
            return; // Skip validation if clipping is enabled
        }

        foreach ($dataSeries as $series) {
            $points = $series->getPoints();
            foreach ($points as $index => $point) {
                // Check X-axis range
                if ($axisConfig->hasXRange()) {
                    $xMin = $axisConfig->getXMin();
                    $xMax = $axisConfig->getXMax();
                    if ($point->x < $xMin || $point->x > $xMax) {
                        throw new \InvalidArgumentException(
                            sprintf(
                                'Data point at index %d has X value %g outside axis range [%g, %g]',
                                $index,
                                $point->x,
                                $xMin,
                                $xMax
                            )
                        );
                    }
                }

                // Check Y-axis range
                if ($axisConfig->hasYRange()) {
                    $yMin = $axisConfig->getYMin();
                    $yMax = $axisConfig->getYMax();
                    if ($point->y < $yMin || $point->y > $yMax) {
                        throw new \InvalidArgumentException(
                            sprintf(
                                'Data point at index %d has Y value %g outside axis range [%g, %g]',
                                $index,
                                $point->y,
                                $yMin,
                                $yMax
                            )
                        );
                    }
                }
            }
        }
    }

    /**
     * Render title and axis labels.
     */
    private function renderLabels(?string $title, ?string $xAxisLabel, ?string $yAxisLabel): string
    {
        $content = '';

        // Render title at top center
        if ($title !== null) {
            $content .= sprintf(
                '<text x="%d" y="25" font-size="18" font-weight="bold" fill="#333" text-anchor="middle">%s</text>',
                $this->width / 2,
                htmlspecialchars($title, ENT_XML1, 'UTF-8')
            );
        }

        // Render X-axis label at bottom center
        if ($xAxisLabel !== null) {
            $content .= sprintf(
                '<text x="%d" y="%d" font-size="14" fill="#666" text-anchor="middle">%s</text>',
                $this->width / 2,
                $this->height - 10,
                htmlspecialchars($xAxisLabel, ENT_XML1, 'UTF-8')
            );
        }

        // Render Y-axis label on left side (rotated)
        if ($yAxisLabel !== null) {
            $content .= sprintf(
                '<text x="15" y="%d" font-size="14" fill="#666" text-anchor="middle" transform="rotate(-90 15 %d)">%s</text>',
                $this->height / 2,
                $this->height / 2,
                htmlspecialchars($yAxisLabel, ENT_XML1, 'UTF-8')
            );
        }

        return $content;
    }
    /**
     * Render legend.
     *
     * @param array<DataSeries> $dataSeries Data series to show in legend
     * @param LegendConfiguration $legendConfig Legend configuration
     * @return string SVG content for legend
     */
    private function renderLegend(array $dataSeries, LegendConfiguration $legendConfig): string
    {
        $content = '';

        $itemHeight = 20;
        $itemWidth = 120;
        $padding = 10;
        $symbolSize = 12;

        $position = $legendConfig->getPosition();
        $fontSize = $legendConfig->getFontSize();

        // Parse colors
        $textColor = ColorParser::parse($legendConfig->getTextColor());
        $bgColor = ColorParser::parse($legendConfig->getBackgroundColor());
        $borderColor = ColorParser::parse($legendConfig->getBorderColor());

        // Calculate legend dimensions
        $legendHeight = count($dataSeries) * $itemHeight + $padding * 2;
        $legendWidth = $itemWidth + $padding * 2;

        // Determine position
        [$x, $y] = match ($position) {
            LegendPosition::Top => [($this->width - $legendWidth) / 2, 10],
            LegendPosition::Right => [$this->width - $legendWidth - 10, ($this->height - $legendHeight) / 2],
            LegendPosition::Bottom => [($this->width - $legendWidth) / 2, $this->height - $legendHeight - 10],
            LegendPosition::Left => [10, ($this->height - $legendHeight) / 2],
        };

        // Draw legend background
        $content .= sprintf(
            '<rect x="%.2f" y="%.2f" width="%d" height="%d" fill="rgb(%d,%d,%d)" stroke="rgb(%d,%d,%d)" stroke-width="1" />',
            $x,
            $y,
            $legendWidth,
            $legendHeight,
            $bgColor['r'],
            $bgColor['g'],
            $bgColor['b'],
            $borderColor['r'],
            $borderColor['g'],
            $borderColor['b']
        );

        // Draw legend items
        $itemY = $y + $padding;
        foreach ($dataSeries as $series) {
            $seriesColor = ColorParser::parse($series->getLineColor() ?? '#3498db');

            // Draw color symbol
            $content .= sprintf(
                '<rect x="%.2f" y="%.2f" width="%d" height="%d" fill="rgb(%d,%d,%d)" />',
                $x + $padding,
                $itemY + ($itemHeight - $symbolSize) / 2,
                $symbolSize,
                $symbolSize,
                $seriesColor['r'],
                $seriesColor['g'],
                $seriesColor['b']
            );

            // Draw series name
            $content .= sprintf(
                '<text x="%.2f" y="%.2f" font-size="%d" fill="rgb(%d,%d,%d)" text-anchor="start">%s</text>',
                $x + $padding + $symbolSize + 8,
                $itemY + $itemHeight / 2 + $fontSize / 3,
                $fontSize,
                $textColor['r'],
                $textColor['g'],
                $textColor['b'],
                htmlspecialchars($series->getName(), ENT_XML1, 'UTF-8')
            );

            $itemY += $itemHeight;
        }

        return $content;
    }

}
