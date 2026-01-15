<?php

declare(strict_types=1);

namespace Codryn\PHPFastChart\Configuration;

/**
 * Defines how to handle data points outside the specified axis range.
 */
enum AxisClipMode
{
    /**
     * Clip data points to the axis range (silently constrain values).
     */
    case Clip;

    /**
     * Throw an exception when data points are outside the axis range.
     */
    case Throw;
}
