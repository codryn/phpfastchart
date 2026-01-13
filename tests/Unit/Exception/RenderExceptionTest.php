<?php

declare(strict_types=1);

namespace Codryn\PHPFastChart\Tests\Unit\Exception;

use Codryn\PHPFastChart\Exception\ChartException;
use Codryn\PHPFastChart\Exception\RenderException;
use PHPUnit\Framework\TestCase;

/**
 * Test RenderException.
 *
 * @covers \Codryn\PHPFastChart\Exception\RenderException
 */
final class RenderExceptionTest extends TestCase
{
    public function testRenderExceptionIsChartException(): void
    {
        $exception = new RenderException('Test exception');

        $this->assertInstanceOf(ChartException::class, $exception);
    }

    public function testRenderExceptionMessage(): void
    {
        $message = 'Rendering failed';
        $exception = new RenderException($message);

        $this->assertSame($message, $exception->getMessage());
    }

    public function testRenderExceptionCode(): void
    {
        $code = 999;
        $exception = new RenderException('Test', $code);

        $this->assertSame($code, $exception->getCode());
    }
}
