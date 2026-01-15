<?php

declare(strict_types=1);

namespace Codryn\PHPFastChart\Exception;

/**
 * Exception thrown when invalid arguments are provided.
 *
 * This includes invalid data types, out-of-range values, or malformed inputs.
 */
final class InvalidArgumentException extends ChartException
{
    // Inherits all behavior from ChartException
}
