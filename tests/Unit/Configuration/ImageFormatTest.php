<?php

declare(strict_types=1);

namespace Codryn\PHPFastChart\Tests\Unit\Configuration;

use Codryn\PHPFastChart\Configuration\ImageFormat;
use PHPUnit\Framework\TestCase;

/**
 * Test ImageFormat enum.
 *
 * @covers \Codryn\PHPFastChart\Configuration\ImageFormat
 */
final class ImageFormatTest extends TestCase
{
    public function testImageFormatHasPng(): void
    {
        $this->assertSame('PNG', ImageFormat::PNG->value);
    }

    public function testImageFormatHasWebp(): void
    {
        $this->assertSame('WEBP', ImageFormat::WEBP->value);
    }

    public function testImageFormatHasSvg(): void
    {
        $this->assertSame('SVG', ImageFormat::SVG->value);
    }

    public function testImageFormatHasAllThreeTypes(): void
    {
        $cases = ImageFormat::cases();

        $this->assertCount(3, $cases);
        $this->assertContains(ImageFormat::PNG, $cases);
        $this->assertContains(ImageFormat::WEBP, $cases);
        $this->assertContains(ImageFormat::SVG, $cases);
    }
}
