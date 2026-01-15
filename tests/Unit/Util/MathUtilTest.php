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

    public function testDataToPixelConvertsCorrectly(): void
    {
        // Map data range [0, 100] to pixel range [50, 450] (400 pixels)
        $pixel = MathUtil::dataToPixel(50.0, 0.0, 100.0, 50.0, 450.0);

        $this->assertSame(250.0, $pixel);
    }

    public function testDataToPixelWithNegativeDataRange(): void
    {
        // Map data range [-50, 50] to pixel range [0, 400]
        $pixel = MathUtil::dataToPixel(0.0, -50.0, 50.0, 0.0, 400.0);

        $this->assertSame(200.0, $pixel);
    }

    public function testDataToPixelAtBoundaries(): void
    {
        // Test at minimum boundary
        $pixelMin = MathUtil::dataToPixel(0.0, 0.0, 100.0, 50.0, 450.0);
        $this->assertSame(50.0, $pixelMin);

        // Test at maximum boundary
        $pixelMax = MathUtil::dataToPixel(100.0, 0.0, 100.0, 50.0, 450.0);
        $this->assertSame(450.0, $pixelMax);
    }

    public function testPixelToDataConvertsCorrectly(): void
    {
        // Map pixel range [50, 450] back to data range [0, 100]
        $data = MathUtil::pixelToData(250.0, 0.0, 100.0, 50.0, 450.0);

        $this->assertSame(50.0, $data);
    }

    public function testCalculateNiceNumberReturns1Or2Or5Multiplied(): void
    {
        // Test various ranges to ensure nice numbers
        $nice1 = MathUtil::calculateNiceNumber(8.7);
        $this->assertSame(10.0, $nice1);

        $nice2 = MathUtil::calculateNiceNumber(3.2);
        $this->assertSame(5.0, $nice2);

        $nice3 = MathUtil::calculateNiceNumber(1.8);
        $this->assertSame(2.0, $nice3);

        $nice4 = MathUtil::calculateNiceNumber(0.7);
        $this->assertSame(1.0, $nice4);
    }

    public function testCalculateNiceNumberWithSmallValues(): void
    {
        $nice = MathUtil::calculateNiceNumber(0.03);
        $this->assertGreaterThan(0, $nice);
        $this->assertLessThanOrEqual(0.1, $nice);
    }

    public function testCalculateNiceNumberWithLargeValues(): void
    {
        $nice = MathUtil::calculateNiceNumber(8700.0);
        $this->assertGreaterThanOrEqual(5000.0, $nice);
        $this->assertLessThanOrEqual(10000.0, $nice);
    }

    public function testPolarToCartesianConvertsAngleAndRadius(): void
    {
        // Test 0 degrees (right)
        $result = MathUtil::polarToCartesian(0.0, 10.0, 0.0, 0.0);
        $this->assertEqualsWithDelta(10.0, $result['x'], 0.001);
        $this->assertEqualsWithDelta(0.0, $result['y'], 0.001);
    }

    public function testPolarToCartesian90Degrees(): void
    {
        // Test 90 degrees (down in standard coordinates, up in screen coordinates)
        $result = MathUtil::polarToCartesian(90.0, 10.0, 0.0, 0.0);
        $this->assertEqualsWithDelta(0.0, $result['x'], 0.001);
        $this->assertEqualsWithDelta(10.0, $result['y'], 0.001);
    }

    public function testPolarToCartesian180Degrees(): void
    {
        // Test 180 degrees (left)
        $result = MathUtil::polarToCartesian(180.0, 10.0, 0.0, 0.0);
        $this->assertEqualsWithDelta(-10.0, $result['x'], 0.001);
        $this->assertEqualsWithDelta(0.0, $result['y'], 0.001);
    }

    public function testPolarToCartesianWithCenterOffset(): void
    {
        // Test with non-zero center coordinates
        $result = MathUtil::polarToCartesian(0.0, 10.0, 50.0, 50.0);
        $this->assertEqualsWithDelta(60.0, $result['x'], 0.001);
        $this->assertEqualsWithDelta(50.0, $result['y'], 0.001);
    }

    public function testPolarToCartesianWithNegativeRadius(): void
    {
        // Negative radius should work (pointing opposite direction)
        $result = MathUtil::polarToCartesian(0.0, -10.0, 0.0, 0.0);
        $this->assertEqualsWithDelta(-10.0, $result['x'], 0.001);
        $this->assertEqualsWithDelta(0.0, $result['y'], 0.001);
    }
}
