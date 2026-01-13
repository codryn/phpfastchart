<?php

declare(strict_types=1);

namespace Codryn\PHPFastChart\Tests\Unit\Configuration;

use Codryn\PHPFastChart\Configuration\LegendPosition;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(LegendPosition::class)]
final class LegendPositionTest extends TestCase
{
    public function testEnumHasAllPositions(): void
    {
        $positions = LegendPosition::cases();

        $this->assertCount(4, $positions);
        $this->assertContains(LegendPosition::Top, $positions);
        $this->assertContains(LegendPosition::Right, $positions);
        $this->assertContains(LegendPosition::Bottom, $positions);
        $this->assertContains(LegendPosition::Left, $positions);
    }

    public function testEnumValuesAreStrings(): void
    {
        $this->assertSame('Top', LegendPosition::Top->value);
        $this->assertSame('Right', LegendPosition::Right->value);
        $this->assertSame('Bottom', LegendPosition::Bottom->value);
        $this->assertSame('Left', LegendPosition::Left->value);
    }
}
