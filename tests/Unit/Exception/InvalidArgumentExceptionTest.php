<?php

declare(strict_types=1);

namespace Codryn\PHPFastChart\Tests\Unit\Exception;

use Codryn\PHPFastChart\Exception\ChartException;
use Codryn\PHPFastChart\Exception\InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * Test InvalidArgumentException.
 *
 * @covers \Codryn\PHPFastChart\Exception\InvalidArgumentException
 */
final class InvalidArgumentExceptionTest extends TestCase
{
    public function testInvalidArgumentExceptionIsChartException(): void
    {
        $exception = new InvalidArgumentException('Test exception');

        $this->assertInstanceOf(ChartException::class, $exception);
    }

    public function testInvalidArgumentExceptionMessage(): void
    {
        $message = 'Invalid argument provided';
        $exception = new InvalidArgumentException($message);

        $this->assertSame($message, $exception->getMessage());
    }

    public function testInvalidArgumentExceptionCode(): void
    {
        $code = 456;
        $exception = new InvalidArgumentException('Test', $code);

        $this->assertSame($code, $exception->getCode());
    }
}
