<?php

declare(strict_types=1);

namespace Codryn\PHPFastChart\Renderer;

use Codryn\PHPFastChart\Chart\ChartType;
use Codryn\PHPFastChart\Configuration\AxisConfiguration;
use Codryn\PHPFastChart\Configuration\ColorConfiguration;
use Codryn\PHPFastChart\Configuration\GridConfiguration;
use Codryn\PHPFastChart\Configuration\LegendConfiguration;

/**
 * Interface for all chart renderers.
 */
interface RendererInterface
{
    /**
     * Render a chart and return the output content.
     *
     * @param ChartType $type Chart type
     * @param array<\Codryn\PHPFastChart\Data\DataSeries> $dataSeries Data series
     * @param ColorConfiguration $colorConfig Color configuration
     * @param GridConfiguration $gridConfig Grid configuration
     * @param AxisConfiguration $axisConfig Axis configuration
     * @param LegendConfiguration $legendConfig Legend configuration
     * @param string|null $title Chart title
     * @param string|null $xAxisLabel X-axis label
     * @param string|null $yAxisLabel Y-axis label
     * @param bool $dataLabelsEnabled Whether data labels are enabled
     * @param array<\Codryn\PHPFastChart\Data\StatisticalOverlay> $statisticalOverlays Statistical overlays to render
     * @return string Rendered chart content (SVG XML or binary image data)
     */
    public function render(
        ChartType $type,
        array $dataSeries,
        ColorConfiguration $colorConfig,
        GridConfiguration $gridConfig,
        AxisConfiguration $axisConfig,
        LegendConfiguration $legendConfig,
        ?string $title,
        ?string $xAxisLabel,
        ?string $yAxisLabel,
        bool $dataLabelsEnabled,
        array $statisticalOverlays = []
    ): string;
}
