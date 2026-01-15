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
 * Integration tests for label rendering functionality.
 *
 * @internal
 */
#[CoversClass(Chart::class)]
final class LabelRenderingTest extends TestCase
{
    public function testRenderChartWithXAxisLabel(): void
    {
        $chart = new Chart(ChartType::Line);
        $series = new DataSeries('Test', [
            new DataPoint(0.0, 10.0),
            new DataPoint(5.0, 20.0),
            new DataPoint(10.0, 15.0),
        ]);

        $chart->setFormat(ImageFormat::SVG)
            ->setSize(400, 300)
            ->addDataSeries($series)
            ->setXAxisLabel('Time (seconds)');

        $svg = $chart->render();

        $this->assertStringContainsString('<svg', $svg);
        $this->assertStringContainsString('<text', $svg);
        $this->assertStringContainsString('Time (seconds)', $svg);
    }

    public function testRenderChartWithYAxisLabel(): void
    {
        $chart = new Chart(ChartType::Line);
        $series = new DataSeries('Test', [
            new DataPoint(0.0, 10.0),
            new DataPoint(5.0, 20.0),
            new DataPoint(10.0, 15.0),
        ]);

        $chart->setFormat(ImageFormat::SVG)
            ->setSize(400, 300)
            ->addDataSeries($series)
            ->setYAxisLabel('Temperature (°C)');

        $svg = $chart->render();

        $this->assertStringContainsString('<svg', $svg);
        $this->assertStringContainsString('<text', $svg);
        $this->assertStringContainsString('Temperature (°C)', $svg);
    }

    public function testRenderChartWithTitle(): void
    {
        $chart = new Chart(ChartType::Line);
        $series = new DataSeries('Test', [
            new DataPoint(0.0, 10.0),
            new DataPoint(5.0, 20.0),
            new DataPoint(10.0, 15.0),
        ]);

        $chart->setFormat(ImageFormat::SVG)
            ->setSize(400, 300)
            ->addDataSeries($series)
            ->setTitle('Monthly Sales Report');

        $svg = $chart->render();

        $this->assertStringContainsString('<svg', $svg);
        $this->assertStringContainsString('<text', $svg);
        $this->assertStringContainsString('Monthly Sales Report', $svg);
    }

    public function testRenderChartWithDataLabels(): void
    {
        $chart = new Chart(ChartType::Line);
        $series = new DataSeries('Test', [
            new DataPoint(0.0, 10.0),
            new DataPoint(5.0, 20.0),
            new DataPoint(10.0, 15.0),
        ]);

        $chart->setFormat(ImageFormat::SVG)
            ->setSize(400, 300)
            ->addDataSeries($series)
            ->enableDataLabels();

        $svg = $chart->render();

        $this->assertStringContainsString('<svg', $svg);
        $this->assertStringContainsString('<text', $svg);
        // Should contain the data point values
        $this->assertStringContainsString('10', $svg);
        $this->assertStringContainsString('20', $svg);
        $this->assertStringContainsString('15', $svg);
    }

    public function testRenderChartWithAllLabels(): void
    {
        $chart = new Chart(ChartType::Bar);
        $series = new DataSeries('Sales', [
            new DataPoint(1.0, 100.0),
            new DataPoint(2.0, 150.0),
            new DataPoint(3.0, 120.0),
        ]);

        $chart->setFormat(ImageFormat::SVG)
            ->setSize(600, 400)
            ->addDataSeries($series)
            ->setTitle('Q1 Sales Performance')
            ->setXAxisLabel('Quarter')
            ->setYAxisLabel('Revenue ($1000s)')
            ->enableDataLabels();

        $svg = $chart->render();

        $this->assertStringContainsString('<svg', $svg);
        $this->assertStringContainsString('Q1 Sales Performance', $svg);
        $this->assertStringContainsString('Quarter', $svg);
        $this->assertStringContainsString('Revenue ($1000s)', $svg);
        $this->assertStringContainsString('100', $svg);
        $this->assertStringContainsString('150', $svg);
        $this->assertStringContainsString('120', $svg);
    }

    public function testRenderChartWithoutLabels(): void
    {
        $chart = new Chart(ChartType::Line);
        $series = new DataSeries('Test', [
            new DataPoint(0.0, 10.0),
            new DataPoint(5.0, 20.0),
        ]);

        $chart->setFormat(ImageFormat::SVG)
            ->setSize(400, 300)
            ->addDataSeries($series);

        $svg = $chart->render();

        // Should render without errors when no labels are set
        $this->assertStringContainsString('<svg', $svg);
        $this->assertStringContainsString('<path', $svg);
    }
}
