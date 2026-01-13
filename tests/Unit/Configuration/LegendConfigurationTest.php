<?php

declare(strict_types=1);

namespace Codryn\PHPFastChart\Tests\Unit\Configuration;

use Codryn\PHPFastChart\Configuration\LegendConfiguration;
use Codryn\PHPFastChart\Configuration\LegendPosition;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(LegendConfiguration::class)]
final class LegendConfigurationTest extends TestCase
{
    public function testDefaultConfiguration(): void
    {
        $config = new LegendConfiguration();

        $this->assertFalse($config->isEnabled());
        $this->assertSame(LegendPosition::Right, $config->getPosition());
        $this->assertSame(12, $config->getFontSize());
        $this->assertSame('#333333', $config->getTextColor());
        $this->assertSame('#FFFFFF', $config->getBackgroundColor());
        $this->assertSame('#CCCCCC', $config->getBorderColor());
    }

    public function testEnableLegend(): void
    {
        $config = new LegendConfiguration();
        $enabled = $config->withEnabled(true);

        $this->assertFalse($config->isEnabled());
        $this->assertTrue($enabled->isEnabled());
    }

    public function testSetPosition(): void
    {
        $config = new LegendConfiguration();
        $positioned = $config->withPosition(LegendPosition::Bottom);

        $this->assertSame(LegendPosition::Right, $config->getPosition());
        $this->assertSame(LegendPosition::Bottom, $positioned->getPosition());
    }

    public function testSetFontSize(): void
    {
        $config = new LegendConfiguration();
        $customFont = $config->withFontSize(14);

        $this->assertSame(12, $config->getFontSize());
        $this->assertSame(14, $customFont->getFontSize());
    }

    public function testSetTextColor(): void
    {
        $config = new LegendConfiguration();
        $customColor = $config->withTextColor('#FF0000');

        $this->assertSame('#333333', $config->getTextColor());
        $this->assertSame('#FF0000', $customColor->getTextColor());
    }

    public function testSetBackgroundColor(): void
    {
        $config = new LegendConfiguration();
        $customBg = $config->withBackgroundColor('#F5F5F5');

        $this->assertSame('#FFFFFF', $config->getBackgroundColor());
        $this->assertSame('#F5F5F5', $customBg->getBackgroundColor());
    }

    public function testSetBorderColor(): void
    {
        $config = new LegendConfiguration();
        $customBorder = $config->withBorderColor('#000000');

        $this->assertSame('#CCCCCC', $config->getBorderColor());
        $this->assertSame('#000000', $customBorder->getBorderColor());
    }

    public function testImmutability(): void
    {
        $config = new LegendConfiguration();
        $modified = $config->withEnabled(true)
            ->withPosition(LegendPosition::Top)
            ->withFontSize(16)
            ->withTextColor('#000000')
            ->withBackgroundColor('#EEEEEE')
            ->withBorderColor('#999999');

        $this->assertNotSame($config, $modified);
        $this->assertFalse($config->isEnabled());
        $this->assertTrue($modified->isEnabled());
    }

    public function testFluentInterface(): void
    {
        $config = new LegendConfiguration();
        $result = $config->withEnabled(true)
            ->withPosition(LegendPosition::Bottom)
            ->withFontSize(14);

        $this->assertInstanceOf(LegendConfiguration::class, $result);
        $this->assertTrue($result->isEnabled());
        $this->assertSame(LegendPosition::Bottom, $result->getPosition());
        $this->assertSame(14, $result->getFontSize());
    }
}
