<?php

declare(strict_types=1);

namespace Codryn\PHPFastChart\Tests\Unit\Configuration;

use Codryn\PHPFastChart\Configuration\AxisClipMode;
use Codryn\PHPFastChart\Configuration\AxisConfiguration;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(AxisConfiguration::class)]
final class AxisConfigurationTest extends TestCase
{
    public function testDefaultConfiguration(): void
    {
        $config = new AxisConfiguration();

        $this->assertNull($config->getXMin());
        $this->assertNull($config->getXMax());
        $this->assertNull($config->getYMin());
        $this->assertNull($config->getYMax());
        $this->assertSame(AxisClipMode::Throw, $config->getClipMode());
        $this->assertFalse($config->hasXRange());
        $this->assertFalse($config->hasYRange());
    }

    public function testWithXRange(): void
    {
        $config = new AxisConfiguration();
        $updated = $config->withXRange(0.0, 100.0);

        $this->assertNotSame($config, $updated);
        $this->assertSame(0.0, $updated->getXMin());
        $this->assertSame(100.0, $updated->getXMax());
        $this->assertTrue($updated->hasXRange());
    }

    public function testWithYRange(): void
    {
        $config = new AxisConfiguration();
        $updated = $config->withYRange(-50.0, 50.0);

        $this->assertNotSame($config, $updated);
        $this->assertSame(-50.0, $updated->getYMin());
        $this->assertSame(50.0, $updated->getYMax());
        $this->assertTrue($updated->hasYRange());
    }

    public function testWithClipMode(): void
    {
        $config = new AxisConfiguration();
        $updated = $config->withClipMode(AxisClipMode::Clip);

        $this->assertNotSame($config, $updated);
        $this->assertSame(AxisClipMode::Clip, $updated->getClipMode());
    }

    public function testFluentInterface(): void
    {
        $config = (new AxisConfiguration())
            ->withXRange(0.0, 100.0)
            ->withYRange(0.0, 200.0)
            ->withClipMode(AxisClipMode::Clip);

        $this->assertSame(0.0, $config->getXMin());
        $this->assertSame(100.0, $config->getXMax());
        $this->assertSame(0.0, $config->getYMin());
        $this->assertSame(200.0, $config->getYMax());
        $this->assertSame(AxisClipMode::Clip, $config->getClipMode());
    }

    public function testInvalidXRangeThrowsException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('X-axis minimum must be less than maximum');

        $config = new AxisConfiguration();
        $config->withXRange(100.0, 0.0);
    }

    public function testInvalidYRangeThrowsException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Y-axis minimum must be less than maximum');

        $config = new AxisConfiguration();
        $config->withYRange(50.0, -50.0);
    }

    public function testEqualRangeThrowsException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Y-axis minimum must be less than maximum');

        $config = new AxisConfiguration();
        $config->withYRange(10.0, 10.0);
    }
}
