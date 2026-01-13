<?php

declare(strict_types=1);

namespace Codryn\PHPFastChart\Tests\Unit\Configuration;

use Codryn\PHPFastChart\Configuration\AxisClipMode;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(AxisClipMode::class)]
final class AxisClipModeTest extends TestCase
{
    public function testClipModeValues(): void
    {
        $this->assertSame('Clip', AxisClipMode::Clip->name);
        $this->assertSame('Throw', AxisClipMode::Throw->name);
    }

    public function testClipModeHasTwoValues(): void
    {
        $cases = AxisClipMode::cases();
        $this->assertCount(2, $cases);
        $this->assertContains(AxisClipMode::Clip, $cases);
        $this->assertContains(AxisClipMode::Throw, $cases);
    }
}
