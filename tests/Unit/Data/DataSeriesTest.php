<?php

declare(strict_types=1);

namespace Codryn\PHPFastChart\Tests\Unit\Data;

use Codryn\PHPFastChart\Data\DataPoint;
use Codryn\PHPFastChart\Data\DataSeries;
use Codryn\PHPFastChart\Exception\InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * Test DataSeries class.
 */
final class DataSeriesTest extends TestCase
{
    public function testDataSeriesCreation(): void
    {
        $points = [
            new DataPoint(1.0, 10.0),
            new DataPoint(2.0, 20.0),
        ];

        $series = new DataSeries('Test Series', $points);

        $this->assertSame('Test Series', $series->getName());
        $this->assertCount(2, $series->getPoints());
    }

    public function testDataSeriesWithColor(): void
    {
        $points = [new DataPoint(1.0, 10.0)];
        $series = new DataSeries('Test', $points, '#FF0000');

        $this->assertSame('#FF0000', $series->getLineColor());
    }

    public function testEmptyDataSeriesThrowsException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('DataSeries must contain at least one point');

        new DataSeries('Empty', []);
    }
}
