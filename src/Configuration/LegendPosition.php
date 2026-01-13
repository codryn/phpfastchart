<?php

declare(strict_types=1);

namespace Codryn\PHPFastChart\Configuration;

/**
 * Enum representing legend positions in a chart.
 */
enum LegendPosition: string
{
    case Top = 'Top';
    case Right = 'Right';
    case Bottom = 'Bottom';
    case Left = 'Left';
}
