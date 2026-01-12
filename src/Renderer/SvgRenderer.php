<?php

declare(strict_types=1);

namespace Codryn\PHPFastChart\Renderer;

use Codryn\PHPFastChart\Chart\ChartType;
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
     * @param string $backgroundColor Background color
     * @return string SVG XML content
     */
    public function render(ChartType $type, array $dataSeries, string $backgroundColor): string
    {
        $svg = sprintf(
            '<svg xmlns="http://www.w3.org/2000/svg" width="%d" height="%d" viewBox="0 0 %d %d">',
            $this->width,
            $this->height,
            $this->width,
            $this->height
        );

        // Background
        $bgColor = ColorParser::parse($backgroundColor);
        $svg .= sprintf(
            '<rect width="100%%" height="100%%" fill="rgb(%d,%d,%d)" />',
            $bgColor['r'],
            $bgColor['g'],
            $bgColor['b']
        );

        // Render based on chart type
        match ($type) {
            ChartType::Line => $svg .= $this->renderLineChart($dataSeries),
            ChartType::Bar => $svg .= $this->renderBarChart($dataSeries),
            default => $svg .= $this->renderPlaceholder($type),
        };

        $svg .= '</svg>';

        return $svg;
    }

    /**
     * @param array<DataSeries> $dataSeries
     */
    private function renderLineChart(array $dataSeries): string
    {
        $content = '';

        // Simple rendering with margins
        $marginLeft = 50;
        $marginRight = 50;
        $marginTop = 50;
        $marginBottom = 50;

        $chartWidth = $this->width - $marginLeft - $marginRight;
        $chartHeight = $this->height - $marginTop - $marginBottom;

        foreach ($dataSeries as $series) {
            $points = $series->getPoints();
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
    private function renderBarChart(array $dataSeries): string
    {
        return '<text x="50%" y="50%" text-anchor="middle" fill="#333">Bar chart (not yet implemented)</text>';
    }

    private function renderPlaceholder(ChartType $type): string
    {
        return sprintf(
            '<text x="50%%" y="50%%" text-anchor="middle" fill="#333">%s chart (not yet implemented)</text>',
            $type->value
        );
    }
}
