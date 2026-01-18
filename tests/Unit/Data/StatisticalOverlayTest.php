<?php

declare(strict_types=1);

namespace Codryn\PHPFastChart\Tests\Unit\Data;

use Codryn\PHPFastChart\Data\StatisticalOverlay;
use Codryn\PHPFastChart\Exception\InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Codryn\PHPFastChart\Data\StatisticalOverlay
 */
final class StatisticalOverlayTest extends TestCase
{
    public function testCreateValidOverlay(): void
    {
        $overlay = new StatisticalOverlay(
            min: 10.0,
            max: 100.0,
            average: 55.0,
            stdDeviation: 15.0,
            color: '#FF0000'
        );

        $this->assertSame(10.0, $overlay->getMin());
        $this->assertSame(100.0, $overlay->getMax());
        $this->assertSame(55.0, $overlay->getAverage());
        $this->assertSame(15.0, $overlay->getStdDeviation());
        $this->assertSame('#FF0000', $overlay->getColor());
    }

    public function testCreateOverlayWithDefaultColor(): void
    {
        $overlay = new StatisticalOverlay(
            min: 10.0,
            max: 100.0,
            average: 55.0,
            stdDeviation: 15.0
        );

        $this->assertSame('#FF0000', $overlay->getColor());
    }

    public function testThrowsWhenMinGreaterThanMax(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Minimum value (100) cannot be greater than maximum value (10)');

        new StatisticalOverlay(
            min: 100.0,
            max: 10.0,
            average: 55.0,
            stdDeviation: 15.0
        );
    }

    public function testThrowsWhenStdDeviationIsNegative(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Standard deviation cannot be negative, got -5');

        new StatisticalOverlay(
            min: 10.0,
            max: 100.0,
            average: 55.0,
            stdDeviation: -5.0
        );
    }

    public function testAllowsMinEqualToMax(): void
    {
        $overlay = new StatisticalOverlay(
            min: 50.0,
            max: 50.0,
            average: 50.0,
            stdDeviation: 0.0
        );

        $this->assertSame(50.0, $overlay->getMin());
        $this->assertSame(50.0, $overlay->getMax());
    }

    public function testAllowsZeroStdDeviation(): void
    {
        $overlay = new StatisticalOverlay(
            min: 50.0,
            max: 50.0,
            average: 50.0,
            stdDeviation: 0.0
        );

        $this->assertSame(0.0, $overlay->getStdDeviation());
    }
}
