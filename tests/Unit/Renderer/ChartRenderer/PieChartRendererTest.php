<?php

declare(strict_types=1);

namespace Codryn\PHPFastChart\Tests\Unit\Renderer\ChartRenderer;

use PHPUnit\Framework\TestCase;

/**
 * Unit tests for Pie chart rendering functionality.
 *
 * Note: Pie chart rendering is implemented directly in SvgRenderer
 * rather than in a separate PieChartRenderer class.
 *
 * @covers \Codryn\PHPFastChart\Chart\ChartType
 */
final class PieChartRendererTest extends TestCase
{
    public function testChartTypeEnumIncludesPie(): void
    {
        $this->assertTrue(
            enum_exists('Codryn\PHPFastChart\Chart\ChartType'),
            'ChartType enum should exist'
        );

        $reflection = new \ReflectionEnum('Codryn\PHPFastChart\Chart\ChartType');
        $cases = array_map(fn ($case) => $case->name, $reflection->getCases());

        $this->assertContains('Pie', $cases, 'ChartType should include Pie');
    }
}
