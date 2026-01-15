<?php

declare(strict_types=1);

namespace Codryn\PHPFastChart\Exception;

/**
 * Exception thrown when chart rendering fails.
 *
 * This includes GD library errors, SVG generation failures, or file system issues.
 */
final class RenderException extends ChartException
{
    // Inherits all behavior from ChartException
}
