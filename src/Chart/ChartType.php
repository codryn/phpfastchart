<?php

declare(strict_types=1);

namespace Codryn\PHPFastChart\Chart;

/**
 * Enum representing supported chart types.
 */
enum ChartType: string
{
    case Bar = 'Bar';
    case Line = 'Line';
    case Pie = 'Pie';
    case Scatter = 'Scatter';
    case Radar = 'Radar';
}
