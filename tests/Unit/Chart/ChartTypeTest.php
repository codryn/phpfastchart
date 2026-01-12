<?php

declare(strict_types=1);

namespace Codryn\PHPFastChart\Tests\Unit\Chart;

use Codryn\PHPFastChart\Chart\ChartType;
use PHPUnit\Framework\TestCase;

/**
 * Test ChartType enum.
 */
final class ChartTypeTest extends TestCase
{
    public function testChartTypeHasBar(): void
    {
        $this->assertSame('Bar', ChartType::Bar->value);
    }

    public function testChartTypeHasLine(): void
    {
        $this->assertSame('Line', ChartType::Line->value);
    }

    public function testChartTypeHasPie(): void
    {
        $this->assertSame('Pie', ChartType::Pie->value);
    }

    public function testChartTypeHasScatter(): void
    {
        $this->assertSame('Scatter', ChartType::Scatter->value);
    }

    public function testChartTypeHasRadar(): void
    {
        $this->assertSame('Radar', ChartType::Radar->value);
    }

    public function testChartTypeHasAllFiveTypes(): void
    {
        $cases = ChartType::cases();

        $this->assertCount(5, $cases);
        $this->assertContains(ChartType::Bar, $cases);
        $this->assertContains(ChartType::Line, $cases);
        $this->assertContains(ChartType::Pie, $cases);
        $this->assertContains(ChartType::Scatter, $cases);
        $this->assertContains(ChartType::Radar, $cases);
    }
}
