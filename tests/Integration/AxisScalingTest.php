<?php

declare(strict_types=1);

namespace Codryn\PHPFastChart\Tests\Integration;

use Codryn\PHPFastChart\Chart\Chart;
use Codryn\PHPFastChart\Chart\ChartType;
use Codryn\PHPFastChart\Configuration\AxisClipMode;
use Codryn\PHPFastChart\Configuration\ImageFormat;
use Codryn\PHPFastChart\Data\DataPoint;
use Codryn\PHPFastChart\Data\DataSeries;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * Integration tests for axis scaling functionality.
 *
 * @internal
 */
#[CoversClass(Chart::class)]
final class AxisScalingTest extends TestCase
{
    public function testManualYAxisRange(): void
    {
        $chart = new Chart(ChartType::Line);
        $series = new DataSeries('Test', [
            new DataPoint(0.0, 50.0),
            new DataPoint(10.0, 75.0),
            new DataPoint(20.0, 60.0),
        ]);

        $chart->setFormat(ImageFormat::SVG)
            ->setSize(400, 300)
            ->addDataSeries($series)
            ->setYAxisRange(0.0, 100.0);

        $svg = $chart->render();

        // Verify SVG contains content and has proper viewBox
        $this->assertStringContainsString('<svg', $svg);
        $this->assertStringContainsString('width="400"', $svg);
        $this->assertStringContainsString('height="300"', $svg);
    }

    public function testManualXAxisRange(): void
    {
        $chart = new Chart(ChartType::Line);
        $series = new DataSeries('Test', [
            new DataPoint(5.0, 50.0),
            new DataPoint(10.0, 75.0),
            new DataPoint(15.0, 60.0),
        ]);

        $chart->setFormat(ImageFormat::SVG)
            ->setSize(400, 300)
            ->addDataSeries($series)
            ->setXAxisRange(0.0, 20.0);

        $svg = $chart->render();

        $this->assertStringContainsString('<svg', $svg);
        $this->assertStringContainsString('width="400"', $svg);
    }

    public function testAutoScalingWithNoExplicitRange(): void
    {
        $chart = new Chart(ChartType::Line);
        $series = new DataSeries('Test', [
            new DataPoint(0.0, 10.0),
            new DataPoint(5.0, 90.0),
            new DataPoint(10.0, 50.0),
        ]);

        $chart->setFormat(ImageFormat::SVG)
            ->setSize(400, 300)
            ->addDataSeries($series);

        $svg = $chart->render();

        // Should auto-scale to fit all data points
        $this->assertStringContainsString('<svg', $svg);
        $this->assertStringContainsString('<path', $svg);
    }

    public function testOutOfRangeDataThrowsException(): void
    {
        $chart = new Chart(ChartType::Line);
        $series = new DataSeries('Test', [
            new DataPoint(0.0, 50.0),
            new DataPoint(10.0, 150.0), // Out of range!
            new DataPoint(20.0, 60.0),
        ]);

        $chart->setFormat(ImageFormat::SVG)
            ->setSize(400, 300)
            ->addDataSeries($series)
            ->setYAxisRange(0.0, 100.0)
            ->setAxisClipMode(AxisClipMode::Throw);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Data point at index 1 has Y value 150 outside axis range [0, 100]');

        $chart->render();
    }

    public function testOutOfRangeDataWithClipMode(): void
    {
        $chart = new Chart(ChartType::Line);
        $series = new DataSeries('Test', [
            new DataPoint(0.0, 50.0),
            new DataPoint(10.0, 150.0), // Will be clipped to 100
            new DataPoint(20.0, 60.0),
        ]);

        $chart->setFormat(ImageFormat::SVG)
            ->setSize(400, 300)
            ->addDataSeries($series)
            ->setYAxisRange(0.0, 100.0)
            ->setAxisClipMode(AxisClipMode::Clip);

        $svg = $chart->render();

        // Should render without exception, clipping the out-of-range data
        $this->assertStringContainsString('<svg', $svg);
        $this->assertStringContainsString('<path', $svg);
    }

    public function testXAxisOutOfRangeWithThrowMode(): void
    {
        $chart = new Chart(ChartType::Line);
        $series = new DataSeries('Test', [
            new DataPoint(0.0, 50.0),
            new DataPoint(25.0, 75.0), // Out of X range!
            new DataPoint(10.0, 60.0),
        ]);

        $chart->setFormat(ImageFormat::SVG)
            ->setSize(400, 300)
            ->addDataSeries($series)
            ->setXAxisRange(0.0, 20.0)
            ->setAxisClipMode(AxisClipMode::Throw);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Data point at index 1 has X value 25 outside axis range [0, 20]');

        $chart->render();
    }

    public function testBothAxesManualRange(): void
    {
        $chart = new Chart(ChartType::Line);
        $series = new DataSeries('Test', [
            new DataPoint(5.0, 25.0),
            new DataPoint(10.0, 50.0),
            new DataPoint(15.0, 75.0),
        ]);

        $chart->setFormat(ImageFormat::SVG)
            ->setSize(400, 300)
            ->addDataSeries($series)
            ->setXAxisRange(0.0, 20.0)
            ->setYAxisRange(0.0, 100.0);

        $svg = $chart->render();

        $this->assertStringContainsString('<svg', $svg);
        $this->assertStringContainsString('<path', $svg);
    }
}
