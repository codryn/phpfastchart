<?php

declare(strict_types=1);

namespace Codryn\PHPFastChart\Tests\Integration;

use Codryn\PHPFastChart\Chart\Chart;
use Codryn\PHPFastChart\Chart\ChartType;
use Codryn\PHPFastChart\Configuration\ImageFormat;
use Codryn\PHPFastChart\Data\DataPoint;
use Codryn\PHPFastChart\Data\DataSeries;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * Integration tests for scatter chart functionality.
 *
 * @internal
 */
#[CoversClass(Chart::class)]
final class ScatterChartTest extends TestCase
{
    public function testRenderBasicScatterChart(): void
    {
        $chart = new Chart(ChartType::Scatter);
        $series = new DataSeries('Points', [
            new DataPoint(5.0, 10.0),
            new DataPoint(15.0, 25.0),
            new DataPoint(20.0, 15.0),
            new DataPoint(30.0, 35.0),
        ]);

        $chart->setFormat(ImageFormat::SVG)
            ->setSize(400, 300)
            ->addDataSeries($series);

        $svg = $chart->render();

        $this->assertStringContainsString('<svg', $svg);
        $this->assertStringContainsString('<circle', $svg);
        $this->assertStringContainsString('width="400"', $svg);
        $this->assertStringContainsString('height="300"', $svg);
    }

    public function testRenderScatterChartWithMultipleSeries(): void
    {
        $chart = new Chart(ChartType::Scatter);
        
        $series1 = new DataSeries('Group A', [
            new DataPoint(10.0, 20.0),
            new DataPoint(20.0, 30.0),
            new DataPoint(30.0, 25.0),
        ], '#FF5733');

        $series2 = new DataSeries('Group B', [
            new DataPoint(15.0, 35.0),
            new DataPoint(25.0, 40.0),
            new DataPoint(35.0, 38.0),
        ], '#33C1FF');

        $chart->setFormat(ImageFormat::SVG)
            ->setSize(600, 400)
            ->addDataSeries($series1)
            ->addDataSeries($series2);

        $svg = $chart->render();

        $this->assertStringContainsString('<svg', $svg);
        $this->assertStringContainsString('<circle', $svg);
        // Should have circles for both series (3 + 3 = 6)
        $this->assertGreaterThanOrEqual(6, substr_count($svg, '<circle'));
    }

    public function testRenderScatterChartWithLabels(): void
    {
        $chart = new Chart(ChartType::Scatter);
        $series = new DataSeries('Temperature vs Humidity', [
            new DataPoint(18.5, 65.0),
            new DataPoint(22.3, 58.0),
            new DataPoint(25.8, 52.0),
            new DataPoint(28.1, 48.0),
        ]);

        $chart->setFormat(ImageFormat::SVG)
            ->setSize(600, 400)
            ->setTitle('Weather Correlation')
            ->setXAxisLabel('Temperature (°C)')
            ->setYAxisLabel('Humidity (%)')
            ->addDataSeries($series);

        $svg = $chart->render();

        $this->assertStringContainsString('<svg', $svg);
        $this->assertStringContainsString('<circle', $svg);
        $this->assertStringContainsString('Weather Correlation', $svg);
        $this->assertStringContainsString('Temperature (°C)', $svg);
        $this->assertStringContainsString('Humidity (%)', $svg);
    }

    public function testRenderScatterChartWithGridLines(): void
    {
        $chart = new Chart(ChartType::Scatter);
        $series = new DataSeries('Data', [
            new DataPoint(5.0, 10.0),
            new DataPoint(10.0, 20.0),
            new DataPoint(15.0, 15.0),
        ]);

        $chart->setFormat(ImageFormat::SVG)
            ->setSize(400, 300)
            ->enableGrid()
            ->setGridColor('#E0E0E0')
            ->addDataSeries($series);

        $svg = $chart->render();

        $this->assertStringContainsString('<svg', $svg);
        $this->assertStringContainsString('<circle', $svg);
        $this->assertStringContainsString('<line', $svg);
        $this->assertStringContainsString('rgb(224,224,224)', $svg);
    }

    public function testRenderScatterChartWithAxisScaling(): void
    {
        $chart = new Chart(ChartType::Scatter);
        $series = new DataSeries('Points', [
            new DataPoint(25.0, 50.0),
            new DataPoint(50.0, 75.0),
            new DataPoint(75.0, 60.0),
        ]);

        $chart->setFormat(ImageFormat::SVG)
            ->setSize(400, 300)
            ->setXAxisRange(0.0, 100.0)
            ->setYAxisRange(0.0, 100.0)
            ->addDataSeries($series);

        $svg = $chart->render();

        $this->assertStringContainsString('<svg', $svg);
        $this->assertStringContainsString('<circle', $svg);
    }

    public function testRenderScatterChartWithDataLabels(): void
    {
        $chart = new Chart(ChartType::Scatter);
        $series = new DataSeries('Points', [
            new DataPoint(10.0, 20.0),
            new DataPoint(20.0, 30.0),
            new DataPoint(30.0, 25.0),
        ]);

        $chart->setFormat(ImageFormat::SVG)
            ->setSize(400, 300)
            ->enableDataLabels()
            ->addDataSeries($series);

        $svg = $chart->render();

        $this->assertStringContainsString('<svg', $svg);
        $this->assertStringContainsString('<circle', $svg);
        $this->assertStringContainsString('<text', $svg);
        // Should contain Y values as labels
        $this->assertStringContainsString('20', $svg);
        $this->assertStringContainsString('30', $svg);
        $this->assertStringContainsString('25', $svg);
    }

    public function testRenderScatterChartWithSinglePoint(): void
    {
        $chart = new Chart(ChartType::Scatter);
        $series = new DataSeries('Single Point', [
            new DataPoint(10.0, 20.0),
        ]);

        $chart->setFormat(ImageFormat::SVG)
            ->setSize(400, 300)
            ->addDataSeries($series);

        $svg = $chart->render();

        $this->assertStringContainsString('<svg', $svg);
        $this->assertStringContainsString('<circle', $svg);
        // Should have exactly 1 circle
        $this->assertEquals(1, substr_count($svg, '<circle'));
    }

    public function testRenderScatterChartWithClipping(): void
    {
        $chart = new Chart(ChartType::Scatter);
        $series = new DataSeries('Points', [
            new DataPoint(-10.0, 20.0), // Out of X range
            new DataPoint(50.0, 120.0),  // Out of Y range
            new DataPoint(25.0, 50.0),   // In range
        ]);

        $chart->setFormat(ImageFormat::SVG)
            ->setSize(400, 300)
            ->setXAxisRange(0.0, 100.0)
            ->setYAxisRange(0.0, 100.0)
            ->setAxisClipMode(\Codryn\PHPFastChart\Configuration\AxisClipMode::Clip)
            ->addDataSeries($series);

        $svg = $chart->render();

        $this->assertStringContainsString('<svg', $svg);
        // Should only have 1 circle (the one in range)
        $this->assertEquals(1, substr_count($svg, '<circle'));
    }
}
