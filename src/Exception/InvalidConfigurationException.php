<?php

declare(strict_types=1);

namespace Codryn\PHPFastChart\Exception;

/**
 * Exception thrown when chart configuration is invalid.
 *
 * This includes incompatible settings, missing required configuration, or conflicting options.
 */
final class InvalidConfigurationException extends ChartException
{
    // Inherits all behavior from ChartException
}
