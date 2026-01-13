<?php

declare(strict_types=1);

namespace Codryn\PHPFastChart\Tests\Unit\Configuration;

use Codryn\PHPFastChart\Configuration\ColorConfiguration;
use PHPUnit\Framework\TestCase;

/**
 * Test ColorConfiguration class.
 *
 * @covers \Codryn\PHPFastChart\Configuration\ColorConfiguration
 */
final class ColorConfigurationTest extends TestCase
{
    public function testDefaultColors(): void
    {
        $config = new ColorConfiguration();

        $this->assertSame('#FFFFFF', $config->getBackgroundColor());
        $this->assertSame('#333333', $config->getAxisColor());
    }

    public function testCustomBackgroundColor(): void
    {
        $config = new ColorConfiguration('#FF0000');

        $this->assertSame('#FF0000', $config->getBackgroundColor());
    }

    public function testCustomAxisColor(): void
    {
        $config = new ColorConfiguration('#FFFFFF', '#0000FF');

        $this->assertSame('#FFFFFF', $config->getBackgroundColor());
        $this->assertSame('#0000FF', $config->getAxisColor());
    }

    public function testWithBackgroundColor(): void
    {
        $config = new ColorConfiguration();
        $newConfig = $config->withBackgroundColor('#00FF00');

        $this->assertSame('#FFFFFF', $config->getBackgroundColor());
        $this->assertSame('#00FF00', $newConfig->getBackgroundColor());
    }

    public function testWithAxisColor(): void
    {
        $config = new ColorConfiguration();
        $newConfig = $config->withAxisColor('#FF00FF');

        $this->assertSame('#333333', $config->getAxisColor());
        $this->assertSame('#FF00FF', $newConfig->getAxisColor());
    }
}
