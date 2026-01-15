<?php

declare(strict_types=1);

namespace Codryn\PHPFastChart\Tests\Unit\Configuration;

use Codryn\PHPFastChart\Configuration\GridConfiguration;
use PHPUnit\Framework\TestCase;

/**
 * Test GridConfiguration class.
 *
 * @covers \Codryn\PHPFastChart\Configuration\GridConfiguration
 */
final class GridConfigurationTest extends TestCase
{
    public function testDefaultConfiguration(): void
    {
        $config = new GridConfiguration();

        $this->assertFalse($config->isEnabled());
        $this->assertTrue($config->showHorizontalLines());
        $this->assertTrue($config->showVerticalLines());
        $this->assertSame('#E0E0E0', $config->getColor());
        $this->assertSame(1.0, $config->getLineWidth());
        $this->assertNull($config->getSpacing());
    }

    public function testEnableGrid(): void
    {
        $config = new GridConfiguration();
        $newConfig = $config->withEnabled(true);

        $this->assertFalse($config->isEnabled());
        $this->assertTrue($newConfig->isEnabled());
    }

    public function testCustomColor(): void
    {
        $config = new GridConfiguration();
        $newConfig = $config->withColor('#FF0000');

        $this->assertSame('#E0E0E0', $config->getColor());
        $this->assertSame('#FF0000', $newConfig->getColor());
    }

    public function testCustomLineWidth(): void
    {
        $config = new GridConfiguration();
        $newConfig = $config->withLineWidth(2.0);

        $this->assertSame(1.0, $config->getLineWidth());
        $this->assertSame(2.0, $newConfig->getLineWidth());
    }

    public function testCustomSpacing(): void
    {
        $config = new GridConfiguration();
        $newConfig = $config->withSpacing(50.0);

        $this->assertNull($config->getSpacing());
        $this->assertSame(50.0, $newConfig->getSpacing());
    }

    public function testHorizontalLinesOnly(): void
    {
        $config = new GridConfiguration();
        $newConfig = $config->withVerticalLines(false);

        $this->assertTrue($config->showVerticalLines());
        $this->assertFalse($newConfig->showVerticalLines());
        $this->assertTrue($newConfig->showHorizontalLines());
    }

    public function testVerticalLinesOnly(): void
    {
        $config = new GridConfiguration();
        $newConfig = $config->withHorizontalLines(false);

        $this->assertTrue($config->showHorizontalLines());
        $this->assertFalse($newConfig->showHorizontalLines());
        $this->assertTrue($newConfig->showVerticalLines());
    }

    public function testFluentInterface(): void
    {
        $config = new GridConfiguration();
        $newConfig = $config->withEnabled(true)
                            ->withColor('#CCCCCC')
                            ->withLineWidth(1.5)
                            ->withSpacing(100.0)
                            ->withHorizontalLines(true)
                            ->withVerticalLines(false);

        $this->assertTrue($newConfig->isEnabled());
        $this->assertSame('#CCCCCC', $newConfig->getColor());
        $this->assertSame(1.5, $newConfig->getLineWidth());
        $this->assertSame(100.0, $newConfig->getSpacing());
        $this->assertTrue($newConfig->showHorizontalLines());
        $this->assertFalse($newConfig->showVerticalLines());
    }
}
