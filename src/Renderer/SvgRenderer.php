<?php

declare(strict_types=1);

namespace Codryn\PHPFastChart\Renderer;

use Codryn\PHPFastChart\Chart\ChartType;
use Codryn\PHPFastChart\Configuration\AxisClipMode;
use Codryn\PHPFastChart\Configuration\AxisConfiguration;
use Codryn\PHPFastChart\Configuration\ColorConfiguration;
use Codryn\PHPFastChart\Configuration\GridConfiguration;
use Codryn\PHPFastChart\Data\DataSeries;
use Codryn\PHPFastChart\Util\ColorParser;
use Codryn\PHPFastChart\Util\MathUtil;

/**
 * SVG renderer for generating charts as SVG XML.
 */
final class SvgRenderer
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
     * @return string SVG XML content
     */
    public function render(
        ChartType $type,
        array $dataSeries,
        ColorConfiguration $colorConfig,
        GridConfiguration $gridConfig,
        AxisConfiguration $axisConfig
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
            ChartType::Line => $svg .= $this->renderLineChart($dataSeries, $colorConfig, $gridConfig, $axisConfig),
            ChartType::Bar => $svg .= $this->renderBarChart($dataSeries, $colorConfig, $gridConfig, $axisConfig),
            default => $svg .= $this->renderPlaceholder($type),
        };

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
        AxisConfiguration $axisConfig
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
        AxisConfiguration $axisConfig
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
}
