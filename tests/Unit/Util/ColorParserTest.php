<?php

declare(strict_types=1);

namespace Codryn\PHPFastChart\Tests\Unit\Util;

use Codryn\PHPFastChart\Exception\InvalidArgumentException;
use Codryn\PHPFastChart\Util\ColorParser;
use PHPUnit\Framework\TestCase;

/**
 * Test ColorParser utility.
 */
final class ColorParserTest extends TestCase
{
    public function testParseHexColor(): void
    {
        $result = ColorParser::parse('#FF5733');

        $this->assertSame(['r' => 255, 'g' => 87, 'b' => 51, 'a' => 1.0], $result);
    }

    public function testParseShortHexColor(): void
    {
        $result = ColorParser::parse('#F57');

        $this->assertSame(['r' => 255, 'g' => 85, 'b' => 119, 'a' => 1.0], $result);
    }

    public function testParseHexColorWithAlpha(): void
    {
        $result = ColorParser::parse('#FF5733', 0.5);

        $this->assertSame(['r' => 255, 'g' => 87, 'b' => 51, 'a' => 0.5], $result);
    }

    public function testParseNamedColorRed(): void
    {
        $result = ColorParser::parse('red');

        $this->assertSame(['r' => 255, 'g' => 0, 'b' => 0, 'a' => 1.0], $result);
    }

    public function testParseNamedColorBlue(): void
    {
        $result = ColorParser::parse('blue');

        $this->assertSame(['r' => 0, 'g' => 0, 'b' => 255, 'a' => 1.0], $result);
    }

    public function testParseNamedColorWhite(): void
    {
        $result = ColorParser::parse('white');

        $this->assertSame(['r' => 255, 'g' => 255, 'b' => 255, 'a' => 1.0], $result);
    }

    public function testParseInvalidColorThrowsException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid color format');

        ColorParser::parse('invalid-color');
    }

    public function testParseInvalidHexColorThrowsException(): void
    {
        $this->expectException(InvalidArgumentException::class);

        ColorParser::parse('#GGGGGG');
    }
}
