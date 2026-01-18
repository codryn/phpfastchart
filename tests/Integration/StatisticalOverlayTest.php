<?php

declare(strict_types=1);

namespace Codryn\PHPFastChart\Tests\Integration;

use Codryn\PHPFastChart\Chart\Chart;
use Codryn\PHPFastChart\Chart\ChartType;
use Codryn\PHPFastChart\Configuration\ImageFormat;
use Codryn\PHPFastChart\Data\DataPoint;
use Codryn\PHPFastChart\Data\DataSeries;
use Codryn\PHPFastChart\Data\StatisticalOverlay;
use Codryn\PHPFastChart\Exception\InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * Integration test for statistical overlay functionality.
 *
 * @covers \Codryn\PHPFastChart\Chart\Chart
 * @covers \Codryn\PHPFastChart\Data\StatisticalOverlay
 * @covers \Codryn\PHPFastChart\Renderer\SvgRenderer
 * @covers \Codryn\PHPFastChart\Renderer\RasterRenderer
 */
final class StatisticalOverlayTest extends TestCase
{
    private string $outputDir;

    protected function setUp(): void
    {
        $this->outputDir = sys_get_temp_dir() . '/phpfastchart_overlay_test_' . uniqid();
        mkdir($this->outputDir, 0777, true);
    }

    protected function tearDown(): void
    {
        // Clean up test files
        if (is_dir($this->outputDir)) {
            $files = glob($this->outputDir . '/*');
            if ($files !== false) {
                foreach ($files as $file) {
                    if (is_file($file)) {
                        unlink($file);
                    }
                }
            }
            rmdir($this->outputDir);
        }
    }

    public function testAddOverlayToLineChart(): void
    {
        $chart = new Chart(ChartType::Line);
        $series = new DataSeries('Test', [
            new DataPoint(1.0, 10.0),
            new DataPoint(2.0, 20.0),
            new DataPoint(3.0, 30.0),
        ], '#0000FF');

        $overlay = new StatisticalOverlay(
            min: 10.0,
            max: 30.0,
            average: 20.0,
            stdDeviation: 8.16,
            color: '#FF0000'
        );

        $chart->setSize(800, 600)
              ->setFormat(ImageFormat::SVG)
              ->addDataSeries($series)
              ->addStatisticalOverlay($overlay);

        $outputPath = $this->outputDir . '/line_with_overlay.svg';
        $chart->generate($outputPath);

        $this->assertFileExists($outputPath);
        $content = file_get_contents($outputPath);
        $this->assertNotFalse($content);

        // Check for overlay elements
        $this->assertStringContainsString('statistical-overlays', $content);
        $this->assertStringContainsString('min=10.00', $content);
        $this->assertStringContainsString('max=30.00', $content);
        $this->assertStringContainsString('avg=20.00', $content);
    }

    public function testAddOverlayToBarChart(): void
    {
        $chart = new Chart(ChartType::Bar);
        $series = new DataSeries('Test', [
            new DataPoint(1.0, 100.0),
            new DataPoint(2.0, 150.0),
            new DataPoint(3.0, 120.0),
        ], '#00FF00');

        $overlay = new StatisticalOverlay(
            min: 100.0,
            max: 150.0,
            average: 123.33,
            stdDeviation: 20.82,
            color: '#00FF00'
        );

        $chart->setSize(800, 600)
              ->setFormat(ImageFormat::SVG)
              ->addDataSeries($series)
              ->addStatisticalOverlay($overlay);

        $outputPath = $this->outputDir . '/bar_with_overlay.svg';
        $chart->generate($outputPath);

        $this->assertFileExists($outputPath);
        $content = file_get_contents($outputPath);
        $this->assertNotFalse($content);

        // Check for overlay elements
        $this->assertStringContainsString('statistical-overlays', $content);
        $this->assertStringContainsString('min=100.00', $content);
        $this->assertStringContainsString('max=150.00', $content);
        $this->assertStringContainsString('avg=123.33', $content);
    }

    public function testAddMultipleOverlays(): void
    {
        $chart = new Chart(ChartType::Line);
        $series = new DataSeries('Test', [
            new DataPoint(1.0, 50.0),
            new DataPoint(2.0, 60.0),
            new DataPoint(3.0, 70.0),
        ], '#0000FF');

        $overlay1 = new StatisticalOverlay(
            min: 50.0,
            max: 70.0,
            average: 60.0,
            stdDeviation: 8.16,
            color: '#FF0000'
        );

        $overlay2 = new StatisticalOverlay(
            min: 48.0,
            max: 72.0,
            average: 59.5,
            stdDeviation: 9.0,
            color: '#00FF00'
        );

        $chart->setSize(800, 600)
              ->setFormat(ImageFormat::SVG)
              ->addDataSeries($series)
              ->addStatisticalOverlay($overlay1)
              ->addStatisticalOverlay($overlay2);

        $outputPath = $this->outputDir . '/line_with_multiple_overlays.svg';
        $chart->generate($outputPath);

        $this->assertFileExists($outputPath);
        $content = file_get_contents($outputPath);
        $this->assertNotFalse($content);

        // Check for both overlays
        $this->assertStringContainsString('min=50.00', $content);
        $this->assertStringContainsString('min=48.00', $content);
        $this->assertStringContainsString('avg=60.00', $content);
        $this->assertStringContainsString('avg=59.50', $content);
    }

    public function testThrowsOnPieChartOverlay(): void
    {
        $chart = new Chart(ChartType::Pie);
        $series = new DataSeries('Test', [
            new DataPoint(1.0, 30.0),
            new DataPoint(2.0, 70.0),
        ]);

        $overlay = new StatisticalOverlay(
            min: 30.0,
            max: 70.0,
            average: 50.0,
            stdDeviation: 20.0
        );

        $chart->addDataSeries($series);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Statistical overlays are only supported for X/Y chart types');
        $this->expectExceptionMessage('Cannot add overlay to Pie chart');

        $chart->addStatisticalOverlay($overlay);
    }

    public function testThrowsOnRadarChartOverlay(): void
    {
        $chart = new Chart(ChartType::Radar);
        $series = new DataSeries('Test', [
            new DataPoint(1.0, 30.0),
            new DataPoint(2.0, 70.0),
            new DataPoint(3.0, 50.0),
        ]);

        $overlay = new StatisticalOverlay(
            min: 30.0,
            max: 70.0,
            average: 50.0,
            stdDeviation: 20.0
        );

        $chart->addDataSeries($series);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Statistical overlays are only supported for X/Y chart types');
        $this->expectExceptionMessage('Cannot add overlay to Radar chart');

        $chart->addStatisticalOverlay($overlay);
    }

    public function testAddOverlayToScatterChart(): void
    {
        $chart = new Chart(ChartType::Scatter);
        $series = new DataSeries('Test', [
            new DataPoint(1.0, 10.0),
            new DataPoint(2.0, 20.0),
            new DataPoint(3.0, 15.0),
        ], '#FF00FF');

        $overlay = new StatisticalOverlay(
            min: 10.0,
            max: 20.0,
            average: 15.0,
            stdDeviation: 4.08,
            color: '#FFFF00'
        );

        $chart->setSize(800, 600)
              ->setFormat(ImageFormat::SVG)
              ->addDataSeries($series)
              ->addStatisticalOverlay($overlay);

        $outputPath = $this->outputDir . '/scatter_with_overlay.svg';
        $chart->generate($outputPath);

        $this->assertFileExists($outputPath);
        $content = file_get_contents($outputPath);
        $this->assertNotFalse($content);

        // Check for overlay elements
        $this->assertStringContainsString('statistical-overlays', $content);
        $this->assertStringContainsString('min=10.00', $content);
        $this->assertStringContainsString('max=20.00', $content);
        $this->assertStringContainsString('avg=15.00', $content);
    }

    public function testPngRenderingWithOverlay(): void
    {
        if (!extension_loaded('gd')) {
            $this->markTestSkipped('GD extension not loaded');
        }

        $chart = new Chart(ChartType::Bar);
        $series = new DataSeries('Test', [
            new DataPoint(1.0, 100.0),
            new DataPoint(2.0, 150.0),
            new DataPoint(3.0, 120.0),
        ], '#0000FF');

        $overlay = new StatisticalOverlay(
            min: 100.0,
            max: 150.0,
            average: 123.33,
            stdDeviation: 20.82,
            color: '#FF0000'
        );

        $chart->setSize(800, 600)
              ->setFormat(ImageFormat::PNG)
              ->addDataSeries($series)
              ->addStatisticalOverlay($overlay);

        $outputPath = $this->outputDir . '/bar_with_overlay.png';
        $chart->generate($outputPath);

        $this->assertFileExists($outputPath);
        $this->assertGreaterThan(0, filesize($outputPath));
    }
}
