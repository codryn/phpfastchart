<?php

declare(strict_types=1);

namespace Codryn\PHPFastChart\Tests\Unit\Util;

use Codryn\PHPFastChart\Exception\InvalidArgumentException;
use Codryn\PHPFastChart\Util\Validator;
use PHPUnit\Framework\TestCase;

/**
 * Test Validator utility.
 */
final class ValidatorTest extends TestCase
{
    public function testValidDimensions(): void
    {
        $this->assertTrue(Validator::validateDimension(800));
        $this->assertTrue(Validator::validateDimension(50));
        $this->assertTrue(Validator::validateDimension(4000));
    }

    public function testInvalidDimensionTooSmall(): void
    {
        $this->assertFalse(Validator::validateDimension(49));
        $this->assertFalse(Validator::validateDimension(0));
        $this->assertFalse(Validator::validateDimension(-100));
    }

    public function testInvalidDimensionTooLarge(): void
    {
        $this->assertFalse(Validator::validateDimension(4001));
        $this->assertFalse(Validator::validateDimension(10000));
    }

    public function testValidColorFormat(): void
    {
        $this->assertTrue(Validator::validateColorFormat('#FF5733'));
        $this->assertTrue(Validator::validateColorFormat('#F57'));
        $this->assertTrue(Validator::validateColorFormat('red'));
        $this->assertTrue(Validator::validateColorFormat('blue'));
    }

    public function testInvalidColorFormat(): void
    {
        $this->assertFalse(Validator::validateColorFormat('invalid'));
        $this->assertFalse(Validator::validateColorFormat('#GGGGGG'));
        $this->assertFalse(Validator::validateColorFormat(''));
    }

    public function testValidRange(): void
    {
        $this->assertTrue(Validator::validateRange(5.0, 0.0, 10.0));
        $this->assertTrue(Validator::validateRange(0.0, 0.0, 10.0));
        $this->assertTrue(Validator::validateRange(10.0, 0.0, 10.0));
    }

    public function testInvalidRange(): void
    {
        $this->assertFalse(Validator::validateRange(-1.0, 0.0, 10.0));
        $this->assertFalse(Validator::validateRange(11.0, 0.0, 10.0));
    }

    public function testValidateRangeThrowsWhenInvalid(): void
    {
        $this->expectException(InvalidArgumentException::class);

        Validator::validateRangeOrThrow(15.0, 0.0, 10.0, 'value');
    }
}
