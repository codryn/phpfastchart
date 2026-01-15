<?php

declare(strict_types=1);

namespace Codryn\PHPFastChart\Tests\Unit\Exception;

use Codryn\PHPFastChart\Exception\ChartException;
use Codryn\PHPFastChart\Exception\InvalidConfigurationException;
use PHPUnit\Framework\TestCase;

/**
 * Test InvalidConfigurationException.
 *
 * @covers \Codryn\PHPFastChart\Exception\InvalidConfigurationException
 */
final class InvalidConfigurationExceptionTest extends TestCase
{
    public function testInvalidConfigurationExceptionIsChartException(): void
    {
        $exception = new InvalidConfigurationException('Test exception');

        $this->assertInstanceOf(ChartException::class, $exception);
    }

    public function testInvalidConfigurationExceptionMessage(): void
    {
        $message = 'Invalid configuration';
        $exception = new InvalidConfigurationException($message);

        $this->assertSame($message, $exception->getMessage());
    }

    public function testInvalidConfigurationExceptionCode(): void
    {
        $code = 789;
        $exception = new InvalidConfigurationException('Test', $code);

        $this->assertSame($code, $exception->getCode());
    }
}
