<?php

declare(strict_types=1);

namespace Codryn\PHPFastChart\Tests\Integration;

use Codryn\PHPFastChart\Chart\Chart;
use Codryn\PHPFastChart\Chart\ChartType;
use Codryn\PHPFastChart\Configuration\ImageFormat;
use Codryn\PHPFastChart\Data\DataPoint;
use Codryn\PHPFastChart\Data\DataSeries;
use PHPUnit\Framework\TestCase;

/**
 * Integration test for grid rendering.
 *
 * @covers \Codryn\PHPFastChart\Chart\Chart
 * @covers \Codryn\PHPFastChart\Renderer\SvgRenderer
 * @covers \Codryn\PHPFastChart\Configuration\GridConfiguration
 * @covers \Codryn\PHPFastChart\Util\MathUtil
 */
final class GridRenderingTest extends TestCase
{
    public function testRenderChartWithHorizontalGridLines(): void
    {
        $chart = new Chart(ChartType::Line);

        $series = new DataSeries('Data', [
            new DataPoint(0.0, 0.0),
            new DataPoint(10.0, 100.0),
        ]);

        $chart->addDataSeries($series)
              ->setFormat(ImageFormat::SVG)
              ->enableGrid();

        $svg = $chart->render();

        $this->assertStringContainsString('<svg', $svg);
        $this->assertStringContainsString('stroke="#E0E0E0"', $svg);
        // Should contain horizontal grid lines (y1 and y2 with same value)
        $this->assertMatchesRegularExpression('/<line[^>]*y1="[0-9.]+"[^>]*y2="[0-9.]+"/', $svg);
    }

    public function testRenderChartWithVerticalGridLines(): void
    {
        $chart = new Chart(ChartType::Line);

        $series = new DataSeries('Data', [
            new DataPoint(0.0, 0.0),
            new DataPoint(100.0, 100.0),
        ]);

        $chart->addDataSeries($series)
              ->setFormat(ImageFormat::SVG)
              ->enableGrid();

        $svg = $chart->render();

        $this->assertStringContainsString('<svg', $svg);
        // Should contain vertical grid lines (x1 and x2 with same value)
        $this->assertMatchesRegularExpression('/<line[^>]*x1="[0-9.]+"[^>]*x2="[0-9.]+"/', $svg);
    }

    public function testRenderChartWithCustomGridColor(): void
    {
        $chart = new Chart(ChartType::Line);

        $series = new DataSeries('Data', [
            new DataPoint(0.0, 0.0),
            new DataPoint(10.0, 100.0),
        ]);

        $chart->addDataSeries($series)
              ->setFormat(ImageFormat::SVG)
              ->setGridColor('#FF0000');

        $svg = $chart->render();

        $this->assertStringContainsString('stroke="rgb(255,0,0)"', $svg);
    }

    public function testRenderChartWithCustomGridSpacing(): void
    {
        $chart = new Chart(ChartType::Line);

        $series = new DataSeries('Data', [
            new DataPoint(0.0, 0.0),
            new DataPoint(100.0, 100.0),
        ]);

        $chart->addDataSeries($series)
              ->setFormat(ImageFormat::SVG)
              ->enableGrid()
              ->setGridSpacing(50.0);

        $svg = $chart->render();

        $this->assertStringContainsString('<svg', $svg);
        // Grid should be rendered with custom spacing
        $this->assertStringContainsString('<line', $svg);
    }

    public function testRenderChartWithoutGrid(): void
    {
        $chart = new Chart(ChartType::Line);

        $series = new DataSeries('Data', [
            new DataPoint(0.0, 0.0),
            new DataPoint(10.0, 100.0),
        ]);

        $chart->addDataSeries($series)
              ->setFormat(ImageFormat::SVG);

        $svg = $chart->render();

        $this->assertStringContainsString('<svg', $svg);
        // Grid color should not appear when grid is disabled
        $gridLineCount = substr_count($svg, 'stroke="#E0E0E0"');
        $this->assertSame(0, $gridLineCount);
    }

    public function testRenderBarChartWithGrid(): void
    {
        $chart = new Chart(ChartType::Bar);

        $series = new DataSeries('Data', [
            new DataPoint(1.0, 50.0),
            new DataPoint(2.0, 75.0),
            new DataPoint(3.0, 100.0),
        ]);

        $chart->addDataSeries($series)
              ->setFormat(ImageFormat::SVG)
              ->enableGrid();

        $svg = $chart->render();

        $this->assertStringContainsString('<svg', $svg);
        $this->assertStringContainsString('stroke="#E0E0E0"', $svg);
        $this->assertStringContainsString('<rect', $svg); // Bar chart has rectangles
    }
}
