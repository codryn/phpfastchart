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
 * Integration tests for PNG and WEBP raster rendering.
 *
 * @internal
 */
#[CoversClass(Chart::class)]
final class RasterRenderingTest extends TestCase
{
    private string $outputDir;

    protected function setUp(): void
    {
        parent::setUp();
        $this->outputDir = sys_get_temp_dir() . '/phpfastchart_tests';
        if (!is_dir($this->outputDir)) {
            mkdir($this->outputDir, 0777, true);
        }
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        // Clean up test files
        if (is_dir($this->outputDir)) {
            $files = glob($this->outputDir . '/*');
            if ($files !== false) {
                array_map('unlink', $files);
            }
            rmdir($this->outputDir);
        }
    }

    public function testGeneratePngLineChart(): void
    {
        if (!extension_loaded('gd')) {
            $this->markTestSkipped('GD extension is not loaded');
        }

        $chart = new Chart(ChartType::Line);
        $series = new DataSeries('Test Series', [
            new DataPoint(1.0, 10.0),
            new DataPoint(2.0, 20.0),
            new DataPoint(3.0, 15.0),
        ]);

        $chart->setFormat(ImageFormat::PNG)
            ->setSize(400, 300)
            ->addDataSeries($series);

        $outputPath = $this->outputDir . '/test_line.png';
        $chart->generate($outputPath);

        $this->assertFileExists($outputPath);
        $this->assertGreaterThan(0, filesize($outputPath));

        // Verify it's a valid PNG
        $imageInfo = getimagesize($outputPath);
        $this->assertIsArray($imageInfo);
        $this->assertSame(IMAGETYPE_PNG, $imageInfo[2]);
        $this->assertSame(400, $imageInfo[0]);
        $this->assertSame(300, $imageInfo[1]);
    }

    public function testGenerateWebpLineChart(): void
    {
        if (!extension_loaded('gd')) {
            $this->markTestSkipped('GD extension is not loaded');
        }

        if (!function_exists('imagewebp')) {
            $this->markTestSkipped('WEBP support is not available in GD');
        }

        $chart = new Chart(ChartType::Line);
        $series = new DataSeries('Test Series', [
            new DataPoint(1.0, 10.0),
            new DataPoint(2.0, 20.0),
            new DataPoint(3.0, 15.0),
        ]);

        $chart->setFormat(ImageFormat::WEBP)
            ->setSize(400, 300)
            ->addDataSeries($series);

        $outputPath = $this->outputDir . '/test_line.webp';
        $chart->generate($outputPath);

        $this->assertFileExists($outputPath);
        $this->assertGreaterThan(0, filesize($outputPath));

        // Verify it's a valid WEBP
        $imageInfo = getimagesize($outputPath);
        $this->assertIsArray($imageInfo);
        $this->assertSame(IMAGETYPE_WEBP, $imageInfo[2]);
        $this->assertSame(400, $imageInfo[0]);
        $this->assertSame(300, $imageInfo[1]);
    }

    public function testGeneratePngBarChart(): void
    {
        if (!extension_loaded('gd')) {
            $this->markTestSkipped('GD extension is not loaded');
        }

        $chart = new Chart(ChartType::Bar);
        $series = new DataSeries('Sales', [
            new DataPoint(1.0, 100.0),
            new DataPoint(2.0, 150.0),
            new DataPoint(3.0, 120.0),
        ]);

        $chart->setFormat(ImageFormat::PNG)
            ->setSize(600, 400)
            ->addDataSeries($series);

        $outputPath = $this->outputDir . '/test_bar.png';
        $chart->generate($outputPath);

        $this->assertFileExists($outputPath);
        $this->assertGreaterThan(0, filesize($outputPath));

        $imageInfo = getimagesize($outputPath);
        $this->assertIsArray($imageInfo);
        $this->assertSame(IMAGETYPE_PNG, $imageInfo[2]);
    }

    public function testGeneratePngScatterChart(): void
    {
        if (!extension_loaded('gd')) {
            $this->markTestSkipped('GD extension is not loaded');
        }

        $chart = new Chart(ChartType::Scatter);
        $series = new DataSeries('Data Points', [
            new DataPoint(5.0, 10.0),
            new DataPoint(10.0, 20.0),
            new DataPoint(15.0, 15.0),
        ]);

        $chart->setFormat(ImageFormat::PNG)
            ->setSize(500, 400)
            ->addDataSeries($series);

        $outputPath = $this->outputDir . '/test_scatter.png';
        $chart->generate($outputPath);

        $this->assertFileExists($outputPath);
        $this->assertGreaterThan(0, filesize($outputPath));
    }

    public function testPngWithGridAndLabels(): void
    {
        if (!extension_loaded('gd')) {
            $this->markTestSkipped('GD extension is not loaded');
        }

        $chart = new Chart(ChartType::Line);
        $series = new DataSeries('Temperature', [
            new DataPoint(1.0, 15.0),
            new DataPoint(2.0, 18.0),
            new DataPoint(3.0, 22.0),
            new DataPoint(4.0, 20.0),
        ], '#e74c3c');

        $chart->setFormat(ImageFormat::PNG)
            ->setSize(800, 600)
            ->setTitle('Weather Data')
            ->setXAxisLabel('Day')
            ->setYAxisLabel('Temperature (°C)')
            ->enableGrid()
            ->addDataSeries($series);

        $outputPath = $this->outputDir . '/test_grid_labels.png';
        $chart->generate($outputPath);

        $this->assertFileExists($outputPath);
        $this->assertGreaterThan(0, filesize($outputPath));
    }

    public function testPngWithLegend(): void
    {
        if (!extension_loaded('gd')) {
            $this->markTestSkipped('GD extension is not loaded');
        }

        $chart = new Chart(ChartType::Line);
        $series1 = new DataSeries('Series A', [
            new DataPoint(1.0, 10.0),
            new DataPoint(2.0, 15.0),
        ], '#3498db');

        $series2 = new DataSeries('Series B', [
            new DataPoint(1.0, 12.0),
            new DataPoint(2.0, 18.0),
        ], '#2ecc71');

        $chart->setFormat(ImageFormat::PNG)
            ->setSize(600, 400)
            ->enableLegend()
            ->addDataSeries($series1)
            ->addDataSeries($series2);

        $outputPath = $this->outputDir . '/test_legend.png';
        $chart->generate($outputPath);

        $this->assertFileExists($outputPath);
        $this->assertGreaterThan(0, filesize($outputPath));
    }

    public function testPngWithMultipleSeries(): void
    {
        if (!extension_loaded('gd')) {
            $this->markTestSkipped('GD extension is not loaded');
        }

        $chart = new Chart(ChartType::Bar);

        $series1 = new DataSeries('Q1', [
            new DataPoint(1.0, 50.0),
            new DataPoint(2.0, 60.0),
        ], '#e74c3c');

        $series2 = new DataSeries('Q2', [
            new DataPoint(1.0, 65.0),
            new DataPoint(2.0, 75.0),
        ], '#3498db');

        $chart->setFormat(ImageFormat::PNG)
            ->setSize(700, 500)
            ->setTitle('Quarterly Comparison')
            ->enableGrid()
            ->enableLegend()
            ->addDataSeries($series1)
            ->addDataSeries($series2);

        $outputPath = $this->outputDir . '/test_multi_series.png';
        $chart->generate($outputPath);

        $this->assertFileExists($outputPath);
        $this->assertGreaterThan(0, filesize($outputPath));
    }

    public function testRenderMethodReturnsBinaryData(): void
    {
        if (!extension_loaded('gd')) {
            $this->markTestSkipped('GD extension is not loaded');
        }

        $chart = new Chart(ChartType::Line);
        $series = new DataSeries('Test', [
            new DataPoint(1.0, 10.0),
            new DataPoint(2.0, 20.0),
        ]);

        $chart->setFormat(ImageFormat::PNG)
            ->setSize(400, 300)
            ->addDataSeries($series);

        $output = $chart->render();

        $this->assertGreaterThan(100, strlen($output));
        // PNG files start with specific magic bytes
        $this->assertStringStartsWith("\x89PNG", $output);
    }
}
