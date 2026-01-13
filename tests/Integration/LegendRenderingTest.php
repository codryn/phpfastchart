<?php

declare(strict_types=1);

namespace Codryn\PHPFastChart\Tests\Integration;

use Codryn\PHPFastChart\Chart\Chart;
use Codryn\PHPFastChart\Chart\ChartType;
use Codryn\PHPFastChart\Configuration\ImageFormat;
use Codryn\PHPFastChart\Configuration\LegendPosition;
use Codryn\PHPFastChart\Data\DataPoint;
use Codryn\PHPFastChart\Data\DataSeries;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * Integration tests for legend rendering functionality.
 *
 * @internal
 */
#[CoversClass(Chart::class)]
final class LegendRenderingTest extends TestCase
{
    public function testRenderChartWithLegendEnabled(): void
    {
        $chart = new Chart(ChartType::Line);

        $series1 = new DataSeries('Series A', [
            new DataPoint(1.0, 10.0),
            new DataPoint(2.0, 20.0),
        ], '#FF5733');

        $series2 = new DataSeries('Series B', [
            new DataPoint(1.0, 15.0),
            new DataPoint(2.0, 25.0),
        ], '#33C1FF');

        $chart->setFormat(ImageFormat::SVG)
            ->setSize(600, 400)
            ->enableLegend()
            ->addDataSeries($series1)
            ->addDataSeries($series2);

        $svg = $chart->render();

        $this->assertStringContainsString('<svg', $svg);
        $this->assertStringContainsString('Series A', $svg);
        $this->assertStringContainsString('Series B', $svg);
    }

    public function testRenderChartWithLegendDisabled(): void
    {
        $chart = new Chart(ChartType::Line);

        $series1 = new DataSeries('Series A', [
            new DataPoint(1.0, 10.0),
            new DataPoint(2.0, 20.0),
        ]);

        $chart->setFormat(ImageFormat::SVG)
            ->setSize(600, 400)
            ->disableLegend()
            ->addDataSeries($series1);

        $svg = $chart->render();

        $this->assertStringContainsString('<svg', $svg);
        // Legend should not appear when disabled
        $legendCount = substr_count($svg, 'Series A');
        // Series name might appear in other contexts, but legend specific rendering should be absent
        $this->assertLessThanOrEqual(1, $legendCount);
    }

    public function testRenderLegendAtRightPosition(): void
    {
        $chart = new Chart(ChartType::Line);

        $series1 = new DataSeries('Data', [
            new DataPoint(1.0, 10.0),
            new DataPoint(2.0, 20.0),
        ]);

        $chart->setFormat(ImageFormat::SVG)
            ->setSize(600, 400)
            ->enableLegend()
            ->setLegendPosition(LegendPosition::Right)
            ->addDataSeries($series1);

        $svg = $chart->render();

        $this->assertStringContainsString('<svg', $svg);
        $this->assertStringContainsString('Data', $svg);
    }

    public function testRenderLegendAtBottomPosition(): void
    {
        $chart = new Chart(ChartType::Line);

        $series1 = new DataSeries('Bottom Legend', [
            new DataPoint(1.0, 10.0),
            new DataPoint(2.0, 20.0),
        ]);

        $chart->setFormat(ImageFormat::SVG)
            ->setSize(600, 400)
            ->enableLegend()
            ->setLegendPosition(LegendPosition::Bottom)
            ->addDataSeries($series1);

        $svg = $chart->render();

        $this->assertStringContainsString('<svg', $svg);
        $this->assertStringContainsString('Bottom Legend', $svg);
    }

    public function testRenderLegendAtTopPosition(): void
    {
        $chart = new Chart(ChartType::Line);

        $series1 = new DataSeries('Top Legend', [
            new DataPoint(1.0, 10.0),
            new DataPoint(2.0, 20.0),
        ]);

        $chart->setFormat(ImageFormat::SVG)
            ->setSize(600, 400)
            ->enableLegend()
            ->setLegendPosition(LegendPosition::Top)
            ->addDataSeries($series1);

        $svg = $chart->render();

        $this->assertStringContainsString('<svg', $svg);
        $this->assertStringContainsString('Top Legend', $svg);
    }

    public function testRenderLegendAtLeftPosition(): void
    {
        $chart = new Chart(ChartType::Line);

        $series1 = new DataSeries('Left Legend', [
            new DataPoint(1.0, 10.0),
            new DataPoint(2.0, 20.0),
        ]);

        $chart->setFormat(ImageFormat::SVG)
            ->setSize(600, 400)
            ->enableLegend()
            ->setLegendPosition(LegendPosition::Left)
            ->addDataSeries($series1);

        $svg = $chart->render();

        $this->assertStringContainsString('<svg', $svg);
        $this->assertStringContainsString('Left Legend', $svg);
    }

    public function testRenderLegendWithMultipleSeries(): void
    {
        $chart = new Chart(ChartType::Bar);

        $series1 = new DataSeries('Q1 Sales', [
            new DataPoint(1.0, 100.0),
            new DataPoint(2.0, 150.0),
        ], '#FF6384');

        $series2 = new DataSeries('Q2 Sales', [
            new DataPoint(1.0, 120.0),
            new DataPoint(2.0, 180.0),
        ], '#36A2EB');

        $series3 = new DataSeries('Q3 Sales', [
            new DataPoint(1.0, 140.0),
            new DataPoint(2.0, 200.0),
        ], '#FFCE56');

        $chart->setFormat(ImageFormat::SVG)
            ->setSize(800, 600)
            ->enableLegend()
            ->addDataSeries($series1)
            ->addDataSeries($series2)
            ->addDataSeries($series3);

        $svg = $chart->render();

        $this->assertStringContainsString('<svg', $svg);
        $this->assertStringContainsString('Q1 Sales', $svg);
        $this->assertStringContainsString('Q2 Sales', $svg);
        $this->assertStringContainsString('Q3 Sales', $svg);
    }

    public function testRenderLegendWithCustomStyling(): void
    {
        $chart = new Chart(ChartType::Scatter);

        $series1 = new DataSeries('Dataset 1', [
            new DataPoint(5.0, 10.0),
            new DataPoint(10.0, 20.0),
        ]);

        $chart->setFormat(ImageFormat::SVG)
            ->setSize(600, 400)
            ->enableLegend()
            ->setLegendTextColor('#FF0000')
            ->setLegendBackgroundColor('#F5F5F5')
            ->setLegendBorderColor('#000000')
            ->addDataSeries($series1);

        $svg = $chart->render();

        $this->assertStringContainsString('<svg', $svg);
        $this->assertStringContainsString('Dataset 1', $svg);
    }
}
