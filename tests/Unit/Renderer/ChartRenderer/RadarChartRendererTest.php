<?php

declare(strict_types=1);

namespace Codryn\PHPFastChart\Tests\Unit\Renderer\ChartRenderer;

use PHPUnit\Framework\TestCase;

/**
 * Unit tests for Radar chart rendering functionality.
 *
 * Note: Radar chart rendering is implemented directly in SvgRenderer
 * rather than in a separate RadarChartRenderer class.
 */
final class RadarChartRendererTest extends TestCase
{
    public function testChartTypeEnumIncludesRadar(): void
    {
        $this->assertTrue(
            enum_exists('Codryn\PHPFastChart\Chart\ChartType'),
            'ChartType enum should exist'
        );

        $reflection = new \ReflectionEnum('Codryn\PHPFastChart\Chart\ChartType');
        $cases = array_map(fn ($case) => $case->name, $reflection->getCases());

        $this->assertContains('Radar', $cases, 'ChartType should include Radar');
    }
}
