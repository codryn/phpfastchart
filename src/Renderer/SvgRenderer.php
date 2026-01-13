<?php

declare(strict_types=1);

namespace Codryn\PHPFastChart\Renderer;

use Codryn\PHPFastChart\Chart\ChartType;
use Codryn\PHPFastChart\Configuration\ColorConfiguration;
use Codryn\PHPFastChart\Data\DataSeries;
use Codryn\PHPFastChart\Util\ColorParser;

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
     * @return string SVG XML content
     */
    public function render(ChartType $type, array $dataSeries, ColorConfiguration $colorConfig): string
    {
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
            ChartType::Line => $svg .= $this->renderLineChart($dataSeries, $colorConfig),
            ChartType::Bar => $svg .= $this->renderBarChart($dataSeries, $colorConfig),
            default => $svg .= $this->renderPlaceholder($type),
        };

        $svg .= '</svg>';
        
        return $svg;
    }

    /**
     * @param array<DataSeries> $dataSeries
     */
    private function renderLineChart(array $dataSeries, ColorConfiguration $colorConfig): string
    {
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
            if (count($points) === 0) {
                continue;
            }
            
            // Find data bounds
            $minX = $points[0]->x;
            $maxX = $points[0]->x;
            $minY = $points[0]->y;
            $maxY = $points[0]->y;
            
            foreach ($points as $point) {
                $minX = min($minX, $point->x);
                $maxX = max($maxX, $point->x);
                $minY = min($minY, $point->y);
                $maxY = max($maxY, $point->y);
            }
            
            // Add padding
            $rangeX = $maxX - $minX;
            $rangeY = $maxY - $minY;
            if ($rangeX === 0.0) {
                $rangeX = 1.0;
            }
            if ($rangeY === 0.0) {
                $rangeY = 1.0;
            }
            
            // Build path
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
    private function renderBarChart(array $dataSeries, ColorConfiguration $colorConfig): string
    {
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

        foreach ($dataSeries as $series) {
            $points = $series->getPoints();
            if (count($points) === 0) {
                continue;
            }

            // Find min/max for scaling
            $minY = $points[0]->y;
            $maxY = $points[0]->y;

            foreach ($points as $point) {
                $minY = min($minY, $point->y);
                $maxY = max($maxY, $point->y);
            }

            $rangeY = $maxY - $minY;
            if ($rangeY === 0.0) {
                $rangeY = 1.0;
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

    private function renderPlaceholder(ChartType $type): string
    {
        return sprintf(
            '<text x="50%%" y="50%%" text-anchor="middle" fill="#333">%s chart (not yet implemented)</text>',
            $type->value
        );
    }
}
