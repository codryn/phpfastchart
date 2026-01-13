<?php

declare(strict_types=1);

namespace Codryn\PHPFastChart\Tests\Unit\Util;

use Codryn\PHPFastChart\Util\MathUtil;
use PHPUnit\Framework\TestCase;

/**
 * Test MathUtil utility.
 *
 * @covers \Codryn\PHPFastChart\Util\MathUtil
 */
final class MathUtilTest extends TestCase
{
    public function testCalculateGridSpacingWithSmallRange(): void
    {
        $spacing = MathUtil::calculateGridSpacing(0.0, 100.0, 400);

        $this->assertGreaterThan(0, $spacing);
        $this->assertLessThanOrEqual(50.0, $spacing);
    }

    public function testCalculateGridSpacingWithLargeRange(): void
    {
        $spacing = MathUtil::calculateGridSpacing(0.0, 1000.0, 400);

        $this->assertGreaterThan(0, $spacing);
        $this->assertLessThanOrEqual(500.0, $spacing);
    }

    public function testCalculateGridSpacingWithNegativeRange(): void
    {
        $spacing = MathUtil::calculateGridSpacing(-50.0, 50.0, 400);

        $this->assertGreaterThan(0, $spacing);
        $this->assertLessThanOrEqual(50.0, $spacing);
    }

    public function testCalculateGridSpacingReturnsNiceNumber(): void
    {
        $spacing = MathUtil::calculateGridSpacing(0.0, 100.0, 400);

        // Nice numbers are typically 1, 2, 5, 10, 20, 50, 100, etc.
        $niceNumbers = [1.0, 2.0, 5.0, 10.0, 20.0, 25.0, 50.0, 100.0, 200.0, 250.0, 500.0];
        $this->assertContains($spacing, $niceNumbers);
    }

    public function testCalculateGridSpacingWithMinimalPixelSize(): void
    {
        $spacing = MathUtil::calculateGridSpacing(0.0, 100.0, 100);

        // With small pixel range, spacing should be larger
        $this->assertGreaterThanOrEqual(10.0, $spacing);
    }

    public function testCalculateGridSpacingReturnsConsistentResults(): void
    {
        $spacing1 = MathUtil::calculateGridSpacing(0.0, 100.0, 400);
        $spacing2 = MathUtil::calculateGridSpacing(0.0, 100.0, 400);

        $this->assertSame($spacing1, $spacing2);
    }
}
