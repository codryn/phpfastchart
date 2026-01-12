<?php

declare(strict_types=1);

namespace Codryn\PHPFastChart\Tests\Unit\Data;

use Codryn\PHPFastChart\Data\DataPoint;
use PHPUnit\Framework\TestCase;

/**
 * Test DataPoint class.
 */
final class DataPointTest extends TestCase
{
    public function testDataPointCreationWithXY(): void
    {
        $point = new DataPoint(10.0, 20.0);

        $this->assertSame(10.0, $point->x);
        $this->assertSame(20.0, $point->y);
        $this->assertNull($point->label);
    }

    public function testDataPointCreationWithLabel(): void
    {
        $point = new DataPoint(10.0, 20.0, 'Point A');

        $this->assertSame(10.0, $point->x);
        $this->assertSame(20.0, $point->y);
        $this->assertSame('Point A', $point->label);
    }

    public function testDataPointIsImmutable(): void
    {
        $point = new DataPoint(10.0, 20.0);

        // Readonly properties cannot be modified - this is a compile-time check
        // We verify immutability by checking the property is readonly
        $reflection = new \ReflectionProperty(DataPoint::class, 'x');
        $this->assertTrue($reflection->isReadOnly());
    }
}
