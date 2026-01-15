<?php

declare(strict_types=1);

namespace Codryn\PHPFastChart\Tests\Unit\Exception;

use Codryn\PHPFastChart\Exception\ChartException;
use PHPUnit\Framework\TestCase;

/**
 * Test ChartException base class.
 *
 * @covers \Codryn\PHPFastChart\Exception\ChartException
 */
final class ChartExceptionTest extends TestCase
{
    public function testChartExceptionIsException(): void
    {
        $exception = new ChartException('Test exception');

        $this->assertInstanceOf(\Exception::class, $exception);
    }

    public function testChartExceptionMessage(): void
    {
        $message = 'Chart error occurred';
        $exception = new ChartException($message);

        $this->assertSame($message, $exception->getMessage());
    }

    public function testChartExceptionCode(): void
    {
        $code = 123;
        $exception = new ChartException('Test', $code);

        $this->assertSame($code, $exception->getCode());
    }

    public function testChartExceptionPrevious(): void
    {
        $previous = new \RuntimeException('Previous exception');
        $exception = new ChartException('Test', 0, $previous);

        $this->assertSame($previous, $exception->getPrevious());
    }
}
