<?php

declare(strict_types=1);

namespace Codryn\PHPFastChart\Configuration;

/**
 * Enum representing supported image formats.
 */
enum ImageFormat: string
{
    case PNG = 'PNG';
    case WEBP = 'WEBP';
    case SVG = 'SVG';
}
